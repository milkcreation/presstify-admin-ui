<?php

/**
 * Fonctionnalités :
 * - Désactivation de la prise en charge de l'Api Rest Wordpress (webservices).
 *
 * Ressources :
 * @see https://wp-mix.com/wordpress-disable-rest-api-header-links/
 */

namespace tiFy\Plugins\AdminUi\Items;

use tiFy\App\Dependency\AbstractAppDependency;

class RestApi extends AbstractAppDependency
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (config('admin-ui.disable_rest_api')) :
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
        // Disable REST API link tag
        \remove_action('wp_head', 'rest_output_link_wp_head', 10);

        // Disable oEmbed Discovery Links
        \remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

        // Disable REST API link in HTTP headers
        \remove_action('template_redirect', 'rest_output_link_header', 11, 0);
    }
}