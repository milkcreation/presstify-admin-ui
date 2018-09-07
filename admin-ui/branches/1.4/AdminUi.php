<?php

/**
 * @name AdminUi
 * @desc Gestion l'interface d'administration de Wordpress.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstiFy
 * @namespace \tiFy\Plugins\AdminUi
 * @version 1.4.1
 */

namespace tiFy\Plugins\AdminUi;

use tiFy\App\Dependency\AbstractAppDependency;

/**
 * Class AdminUi
 * @package tiFy\Plugins\AdminUi
 *
 * Activation :
 * ----------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\AdminUi\AdminUiServiceProvider à la liste des fournisseurs de services
 *     chargés automatiquement par l'application.
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
 * Configuration :
 * ----------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier admin-ui.php
 * @see /vendor/presstify-plugins/admin-ui/Resources/config/admin-ui.php Exemple de configuration
 */
class AdminUi extends AbstractAppDependency
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->app->appAddAction('admin_bar_menu', [$this, 'admin_bar_menu'], 11);
        $this->app->appAddFilter('admin_footer_text', [$this, 'admin_footer_text']);
    }

    /**
     * Initialisation de la barre d'administration.
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     *
     * @return void
     */
    public function admin_bar_menu($wp_admin_bar)
    {
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
    }

    /**
     * Personnalisation du pied de page de l'interface d'administration.
     *
     * @param string $text
     *
     * @return string
     */
    public function admin_footer_text($text = '')
    {
        return config('admin-ui.admin_footer_text', '') ? : $text;
    }
}
