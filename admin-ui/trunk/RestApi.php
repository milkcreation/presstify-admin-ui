<?php

/**
 * @see https://wp-mix.com/wordpress-disable-rest-api-header-links/
 */

namespace tiFy\Plugins\AdminUi;

use tiFy\Apps\AppController;

class RestApi extends AppController
{
    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function appBoot()
    {
        $this->appAddAction('init');
    }

    /**
     * Initialisation globale de Wordpress.
     */
    public function init()
    {
        // Disable REST API link tag
        remove_action('wp_head', 'rest_output_link_wp_head', 10);

        // Disable oEmbed Discovery Links
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

        // Disable REST API link in HTTP headers
        remove_action('template_redirect', 'rest_output_link_header', 11, 0);
    }
}