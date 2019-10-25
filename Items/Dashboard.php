<?php

/**
 * Fonctionnalités :
 * - Désactivation des éléments du tableau de bord de l'interface d'administration de Wordpress.
 */

namespace tiFy\Plugins\AdminUi\Items;

class Dashboard
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('admin-ui.remove_dashboard_meta_box', [])) :
            add_action('admin_init', [$this, 'admin_init']);
        endif;

        if ($panels = config('admin-ui.remove_dashboard_panel', [])) :
            foreach($panels as $panel) :
                remove_action("{$panel}_panel", "wp_{$panel}_panel");
            endforeach;
        endif;
    }

    /**
     * Initialisation de l'interface d'administration de Wordpress.
     *
     * @return void
     */
    public function admin_init()
    {
        foreach (config('admin-ui.remove_dashboard_meta_box', []) as $metabox => $context) :
            if (is_numeric($metabox)) :
                \remove_meta_box('dashboard_' . $context, 'dashboard', false);
            elseif (is_string($metabox)) :
                remove_meta_box('dashboard_' . $metabox, 'dashboard', $context);
            endif;
        endforeach;
    }
}