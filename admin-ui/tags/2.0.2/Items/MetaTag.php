<?php

/**
 * Fonctionnalités :
 * - Désactivation de la prise en charge des meta tags de Wordpress.
 */

namespace tiFy\Plugins\AdminUi\Items;

use tiFy\App\Dependency\AbstractAppDependency;

class MetaTag extends AbstractAppDependency
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (config('admin-ui.disable_meta_tag')) :
            $this->app->appAddAction('init', [$this, 'init']);
        endif;
    }

    /**
     * Initialisation globale de Wordpress.
     *
     * @return null
     */
    public function init()
    {
        \remove_action('wp_head', 'wp_generator');
        \remove_action('wp_head', 'wp_shortlink_wp_head', 10);
        \remove_action('wp_head', 'wp_dlmp_l10n_style');
        \remove_action('wp_head', 'rsd_link');
        \remove_action('wp_head', 'wlwmanifest_link');
    }
}