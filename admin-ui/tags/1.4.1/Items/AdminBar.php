<?php

/**
 * Fonctionnalités :
 * - Désactivation des entrées de menu de la barre d'administration.
 */

namespace tiFy\Plugins\AdminUi\Items;

use tiFy\App\Dependency\AbstractAppDependency;

class AdminBar extends AbstractAppDependency
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (config('admin-ui.remove_admin_bar_menu', [])) :
            $this->app->appAddAction('wp_before_admin_bar_render', [$this, 'wp_before_admin_bar_render']);
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