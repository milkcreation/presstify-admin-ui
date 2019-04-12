<?php

/**
 * Fonctionnalités :
 * - Désactivation des entrées de menu de la barre d'administration.
 */

namespace tiFy\Plugins\AdminUi\Items;

class AdminBar
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('admin-ui.remove_admin_bar_menu', [])) :
            add_action('wp_before_admin_bar_render', [$this, 'wp_before_admin_bar_render']);
        endif;
    }

    /**
     * Pré-affichage de la barre d'administration de Wordpress.
     *
     * @return void
     */
    public function wp_before_admin_bar_render()
    {
        global $wp_admin_bar;

        foreach (config('admin-ui.remove_admin_bar_menu', []) as $admin_bar_node) :
            $wp_admin_bar->remove_node($admin_bar_node);
        endforeach;
    }
}