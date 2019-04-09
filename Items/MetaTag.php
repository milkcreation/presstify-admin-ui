<?php

/**
 * Fonctionnalités :
 * - Désactivation de la prise en charge des meta tags de Wordpress.
 */

namespace tiFy\Plugins\AdminUi\Items;

class MetaTag
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('admin-ui.disable_meta_tag')) :
            add_action('init', [$this, 'init']);
        endif;
    }

    /**
     * Initialisation globale de Wordpress.
     *
     * @return null
     */
    public function init()
    {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'wp_dlmp_l10n_style');
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }
}