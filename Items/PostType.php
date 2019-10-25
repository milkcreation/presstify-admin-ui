<?php

/**
 * Fonctionnalités :
 * - Désactivation des metaboxes des types de post.
 * - Désactivation de la prise en charge du support de fonctionnalités des types de post.
 * - Désactivation de la prise en charge du type de post article (post).
 *
 * Ressources :
 * @see https://github.com/tonykwon/wp-disable-posts
 */

namespace tiFy\Plugins\AdminUi\Items;

use WP_Admin_Bar;
use WP_Post_Type;

class PostType
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('init', [$this, 'remove_post_type_support'], 9999);

        add_action('add_meta_boxes', [$this, 'remove_post_type_metabox'], 9999);

        if (config('admin-ui.disable_post')) :
            global $pagenow;

            add_action('admin_init', [$this, 'disable_post_dashboard_meta_box']);
            add_action('admin_menu', [$this, 'disable_post_remove_menu']);
            add_filter('nav_menu_meta_box_object', [$this, 'disable_post_nav_menu_meta_box_object']);
            add_action('wp_before_admin_bar_render', [$this, 'disable_post_wp_before_admin_bar_render']);

            /* checks the request and redirects to the dashboard */
            add_action('init', [$this, 'disallow_post_type_post']);

            /* removes Post Type `Post` related menus from the sidebar menu */
            add_action('admin_menu', [$this, 'remove_post_type_post']);

            if (!is_admin() && ($pagenow != 'wp-login.php')) :
                /* need to return a 404 when post_type `post` objects are found */
                add_action('posts_results', [$this, 'check_post_type']);
            endif;
        endif;
    }

    /**
     * Suppression des attributs de support des types de post.
     *
     * @return void
     */
    public function remove_post_type_support()
    {
        foreach (array_keys(config('admin-ui', [])) as $key) :
            if (!preg_match('/^remove_support_(.*)/', $key, $match)) :
                continue;
            endif;

            $post_type = $match[1];

            if (!post_type_exists($post_type)) :
                continue;
            endif;

            foreach (config("admin-ui.{$key}") as $support) :
                remove_post_type_support($post_type, $support);
            endforeach;
        endforeach;
    }

    /**
     * Suppression de metaboxes par type de post.
     *
     * @return void
     */
    public function remove_post_type_metabox()
    {
        foreach (array_keys(config('admin-ui', [])) as $key) :
            if (!preg_match('/^remove_meta_box_(.*)/', $key, $match)) :
                continue;
            endif;

            $post_type = $match[1];

            if (!post_type_exists($post_type)) :
                continue;
            endif;

            foreach (config("admin-ui.{$key}") as $metabox => $context) :
                if (is_numeric($metabox)) :
                    $metabox = $context;
                    $context = false;
                endif;

                remove_meta_box($metabox, $post_type, $context);

                // Hack Wordpress : Maintient du support de la modification du permalien
                if ($metabox === 'slugdiv') :
                    add_action(
                        'edit_form_before_permalink',
                        function ($post) use ($post_type) {
                            if ($post->post_type !== $post_type) :
                                return;
                            endif;

                            $editable_slug = apply_filters('editable_slug', $post->post_name, $post);
                            echo "<input name=\"post_name\" type=\"hidden\" size=\"13\" id=\"post_name\" value=\"" . esc_attr($editable_slug) . "\" autocomplete=\"off\" />";
                        });
                endif;
            endforeach;
        endforeach;
    }

    /**
     * Désactivation du type de post article (post).
     * -----------------------------------------------------------------------------------------------------------------
     */
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
     * @param WP_Post_Type $post_type
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
        /** @var WP_Admin_Bar $wp_admin_bar */
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('new-post');
    }

    /**
     * checks the request and redirects to the dashboard
     * if the user attempts to access any `post` related links
     *
     * @return void
     */
    public function disallow_post_type_post()
    {
        global $pagenow, $wp_post_types;

        if (isset($wp_post_types['post'])) :
            $wp_post_types['post']->exclude_from_search = true;
        endif;

        switch ($pagenow) :
            case 'edit.php':
                if (request()->query('post_type') === 'post') :
                    wp_safe_redirect(get_admin_url(), 301);
                    exit;
                endif;
                break;
            case 'edit-tags.php':
            case 'post-new.php':
                if (
                    !array_key_exists('post_type', request()->query()) &&
                    !array_key_exists('taxonomy', request()->query()) &&
                    !request()->post()
                ) :
                    wp_safe_redirect(get_admin_url(), 301);
                    exit;
                endif;
                break;
        endswitch;
    }

    /**
     * loops through $menu and $submenu global arrays to remove any `post` related menus and submenu items
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
        if ($instance !== false) :
            $posts = []; // we are querying for post type `post`
        endif;

        return $posts;
    }
}