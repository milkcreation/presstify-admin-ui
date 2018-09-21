<?php

/**
 * Fonctionnalités :
 * - Désactivation de la prise en charge des catégories du type post.
 * - Désactivation de la prise en charge des mots clefs (etiquettes) du type post.
 * - Désactivation de la prise en charge de taxonomies pour un type post.
 */

namespace tiFy\Plugins\AdminUi\Items;

class Taxonomy
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('admin-ui.disable_post_category', false)) :
            add_action('init', [$this, 'disable_post_category']);
        endif;

        if (config('admin-ui.disable_post_tag', false)) :
            add_action('init', [$this, 'disable_post_tag']);
        endif;

        add_action('init', [$this, 'unregister_taxonomy_for_object_type'], 9999);
    }

    /**
     * Désactivation des catégories du type post.
     *
     * @return void
     */
    public function disable_post_category()
    {
        global $wp_taxonomies;

        if (isset($wp_taxonomies['category'])) :
            $wp_taxonomies['category']->show_in_nav_menus = false;
        endif;

        unregister_taxonomy_for_object_type('category', 'post');
    }

    /**
     * Désactivation des mots clefs (etiquettes) du type post.
     *
     * @return void
     */
    public function disable_post_tag()
    {
        global $wp_taxonomies;

        if (isset($wp_taxonomies['post_tag'])) :
            $wp_taxonomies['post_tag']->show_in_nav_menus = false;
        endif;

        unregister_taxonomy_for_object_type('post_tag', 'post');
    }

    /**
     * Désactivation des taxonomie pour un type de post.
     *
     * @return void
     */
    public function unregister_taxonomy_for_object_type()
    {
        global $wp_taxonomies;

        foreach (array_keys(config('admin-ui', [])) as $key) :
            if (!preg_match('/^unregister_taxonomy_for_(.*)/', $key, $match)) :
                continue;
            endif;
            $post_type = $match[1];

            if (!post_type_exists($post_type)) :
                continue;
            endif;

            foreach (config("admin-ui.{$key}", []) as $taxonomy) :
                if (isset($wp_taxonomies[$taxonomy])) :
                    $wp_taxonomies[$taxonomy]->show_in_nav_menus = false;
                endif;

                unregister_taxonomy_for_object_type($taxonomy, $post_type[1]);
            endforeach;
        endforeach;
    }
}