<?php

/**
 * Fonctionnalités :
 * - Désactivation de la prise en charge des commentaires.
 */

namespace tiFy\Plugins\AdminUi\Items;

class Comment
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('admin-ui.disable_comment')) :
            add_action('admin_menu', [$this, 'admin_menu']);
            add_action('init', [$this, 'init']);
            add_action('wp_before_admin_bar_render', [$this, 'wp_before_admin_bar_render']);
            add_action('wp_widgets_init', [$this, 'wp_widgets_init']);
        endif;
    }

    /**
     * Chargement du menu de navigation de l'interface d'administration.
     *
     * @return null
     */
    public function admin_menu()
    {
        remove_menu_page('edit-comments.php');
        remove_submenu_page('options-general.php', 'options-discussion.php');
    }

    /**
     * Initialisation globale de Wordpress.
     *
     * @return null
     */
    public function init()
    {
        remove_post_type_support('post', 'comments');
        remove_post_type_support('page', 'comments');
        update_option('default_comment_status', 0);
    }

    /**
     * Pré-affichage de la barre d'administration de Wordpress.
     *
     * @return null
     */
    public function wp_before_admin_bar_render()
    {
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('comments');

        if (is_multisite()) :
            foreach (get_sites() as $site) :
                $wp_admin_bar->remove_menu('blog-' . $site->blog_id . '-c');
            endforeach;
        endif;
    }

    /**
     * Initialisation des widgets de Wordpress.
     *
     * @return null
     */
    public function wp_widgets_init()
    {
        unregister_widget('WP_Widget_Recent_Comments');
    }
}