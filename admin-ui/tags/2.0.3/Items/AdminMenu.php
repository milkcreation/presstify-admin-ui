<?php

/**
 * Fonctionnalités :
 * - Désactivation des entrées de menu de l'interface d'administration de Wordpress.
 */

namespace tiFy\Plugins\AdminUi\Items;

class AdminMenu
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('admin-ui.remove_menu', [])) :
            add_action('admin_menu', [$this, 'admin_menu']);
        endif;
    }

    /**
     * Chargement du menu de l'interface d'administration.
     *
     * @return null
     */
    public function admin_menu()
    {
        foreach (config('admin-ui.remove_menu', []) as $menu) :
            switch ($menu) :
                default :
                    remove_menu_page($menu);
                    break;
                case 'dashboard'     :
                    remove_menu_page('index.php');
                    break;
                case 'posts'         :
                    remove_menu_page('edit.php');
                    break;
                case 'media'         :
                    remove_menu_page('upload.php');
                    break;
                case 'pages'        :
                    remove_menu_page('edit.php?post_type=page');
                    break;
                case 'comments'        :
                    \remove_menu_page('edit-comments.php');
                    break;
                case 'appearence'    :
                    remove_menu_page('themes.php');
                    break;
                case 'plugins'    :
                    remove_menu_page('plugins.php');
                    break;
                case 'users'    :
                    remove_menu_page('users.php');
                    break;
                case 'tools'    :
                    remove_menu_page('tools.php');
                    break;
                case 'settings'    :
                    remove_menu_page('options-general.php');
                    break;
            endswitch;
        endforeach;
    }
}