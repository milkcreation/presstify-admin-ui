<?php

namespace tiFy\Plugins\AdminUi;

use tiFy\Apps\AppController;

/**
 * @see https://github.com/tonykwon/wp-disable-posts
 */
class Posts extends AppController
{
    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function appBoot()
    {
        global $pagenow;

        $this->appAddAction('admin_init', 'disable_post_dashboard_meta_box');
        $this->appAddAction('admin_menu', 'disable_post_remove_menu');
        $this->appAddFilter('nav_menu_meta_box_object', 'disable_post_nav_menu_meta_box_object');
        $this->appAddAction('wp_before_admin_bar_render', 'disable_post_wp_before_admin_bar_render');

        /* checks the request and redirects to the dashboard */
        $this->appAddAction('init', [$this, 'disallow_post_type_post']);
        /* removes Post Type `Post` related menus from the sidebar menu */
        $this->appAddAction('admin_menu', [$this, 'remove_post_type_post']);
        if (!is_admin() && ($pagenow != 'wp-login.php')) :
            /* need to return a 404 when post_type `post` objects are found */
            $this->appAddAction('posts_results', [$this, 'check_post_type']);
            /* do not return any instances of post_type `post` */
            $this->appAddFilter('pre_get_posts', [$this, 'remove_from_search_filter']);
        endif;
    }

    /**
     * Suppression de la metaboxe de tableau de bord - édition rapide.
     *
     * @return void
     */
    public function disable_post_dashboard_meta_box()
    {
        remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');
    }

    /**
     * Suppression du menu d'édition.
     *
     * @return void
     */
    public function disable_post_remove_menu()
    {
        remove_menu_page('edit.php');
    }

    /**
     * Désactivation de la metaboxe de menu
     *
     * @param string $post_type
     *
     * @return boolean|string
     */
    public function disable_post_nav_menu_meta_box_object($post_type)
    {
        if ($post_type->name === 'post') :
            return false;
        else :
            return $post_type;
        endif;
    }

    /**
     * Désactivation de l'entrée de menu "nouveau" de la barre d'administration
     *
     * @return void
     */
    public function disable_post_wp_before_admin_bar_render()
    {
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('new-post');
    }


    /**
     * checks the request and redirects to the dashboard
     * if the user attempts to access any `post` related links
     *
     * @param none
     *
     * @return void
     */
    public function disallow_post_type_post()
    {
        global $pagenow, $wp;

        switch ($pagenow) :
            case 'edit.php':
                if ($this->appRequest('GET')->get('post_type') === 'post') :
                    wp_safe_redirect(get_admin_url(), 301);
                    exit;
                endif;
            case 'edit-tags.php':
            case 'post-new.php':
                if (!array_key_exists('post_type', $this->appRequest('GET')->all()) && !array_key_exists('taxonomy', $this->appRequest('GET')->all()) && !$this->appRequest('POST')->all()) :
                    wp_safe_redirect(get_admin_url(), 301);
                    exit;
                endif;
                break;
        endswitch;
    }

    /**
     * loops through $menu and $submenu global arrays to remove any `post` related menus and submenu items
     *
     * @param none
     *
     * @return void
     */
    public function remove_post_type_post()
    {
        global $menu, $submenu;

        /*
            edit.php
            post-new.php
            edit-tags.php?taxonomy=category
            edit-tags.php?taxonomy=post_tag
         */

        $done = false;
        foreach ($menu as $k => $v) {
            foreach ($v as $key => $val) {
                switch ($val) {
                    case 'Posts':
                        unset($menu[$k]);
                        $done = true;
                        break;
                }
            }
            /* bail out as soon as we are done */
            if ($done) {
                break;
            }
        }
        $done = false;
        foreach ($submenu as $k => $v) {
            switch ($k) {
                case 'edit.php':
                    unset($submenu[$k]);
                    $done = true;
                    break;
            }
            /* bail out as soon as we are done */
            if ($done) {
                break;
            }
        }
    }

    /**
     * checks the SQL statement to see if we are trying to fetch post_type `post`
     *
     * @param array $posts ,  found posts based on supplied SQL Query ($wp_query->request)
     *
     * @return array $posts, found posts
     */
    public function check_post_type($posts = [])
    {
        global $wp_query;
        $look_for = "wp_posts.post_type = 'post'";
        $instance = strpos($wp_query->request, $look_for);
        /*
            http://localhost/?m=2013		- yearly archives
            http://localhost/?m=201303		- monthly archives
            http://localhost/?m=20130327	- daily archives
            http://localhost/?cat=1			- category archives
            http://localhost/?tag=foobar	- tag archives
            http://localhost/?p=1			- single post
        */
        if ($instance !== false) {
            $posts = []; // we are querying for post type `post`
        }
        return $posts;
    }

    /**
     * excludes post type `post` to be returned from search
     *
     * @param null
     *
     * @return object $query, wp_query object
     */
    public function remove_from_search_filter($query)
    {
        if (!is_search()) :
            return $query;
        endif;

        $post_types = get_post_types();
        if (array_key_exists('post', $post_types)) :
            /* exclude post_type `post` from the query results */
            unset($post_types['post']);
        endif;

        $query->set('post_type', array_values($post_types));

        return $query;
    }
}