<?php

/**
 * Fonctionnalités :
 * - Désactivation des éléments du tableau de bord de l'interface d'administration de Wordpress.
 */

namespace tiFy\Plugins\AdminUi\Items;

use tiFy\App\Dependency\AbstractAppDependency;

class Dashboard extends AbstractAppDependency
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (config('admin-ui.remove_dashboard_meta_box', [])) :
            $this->app->appAddAction('admin_init', [$this, 'admin_init']);
        endif;

        if ($panels = config('admin-ui.remove_dashboard_panel', [])) :
            foreach($panels as $panel) :
                \remove_action("{$panel}_panel", "wp_{$panel}_panel");
            endforeach;
        endif;
    }

    /**
     * Initialisation de l'interface d'administration de Wordpress.
     *
     * @return null
     */
    public function admin_init()
    {
        foreach (config('admin-ui.remove_dashboard_meta_box', []) as $metabox => $context) :
            if (\is_numeric($metabox)) :
                \remove_meta_box('dashboard_' . $context, 'dashboard', false);
            elseif (\is_string($metabox)) :
                \remove_meta_box('dashboard_' . $metabox, 'dashboard', $context);
            endif;
        endforeach;
    }
}