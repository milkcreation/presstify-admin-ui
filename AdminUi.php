<?php

namespace tiFy\Plugins\AdminUi;

/**
 * @desc Extension PresstiFy de gestion l'interface d'administration de Wordpress.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\AdminUi
 * @version 2.0.12
 *
 * USAGE :
 * Activation
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\AdminUi\AdminUiServiceProvider à la liste des fournisseurs de services.
 * ex.
 * <?php
 * ...
 * use tiFy\Plugins\AdminUi\AdminUiServiceProvider;
 * ...
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          AdminUiServiceProvider::class
 *          ...
 *      ]
 * ];
 *
 * Configuration
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier admin-ui.php
 * @see Resources/config/admin-ui.php
 */
class AdminUi
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('admin_bar_menu', function () {
            /** @var \WP_Admin_Bar $wp_admin_bar */
            if ($admin_bar_menu_logo = config('admin-ui.admin_bar_menu_logo', [])) :
                $wp_admin_bar->remove_menu('wp-logo');

                foreach ($admin_bar_menu_logo as $node) :
                    if (!empty($node['group'])) :
                        $wp_admin_bar->add_group($node);
                    else :
                        $wp_admin_bar->add_menu($node);
                    endif;
                endforeach;
            endif;
        }, 11);

        add_filter('admin_footer_text', function ($text = '') {
            return config('admin-ui.admin_footer_text', '') ? : $text;
        }, 999999);
    }
}
