<?php

/**
 * Fonctionnalités :
 * - Désactivation des widgets natifs de Wordpress et personnalisés.
 */

namespace tiFy\Plugins\AdminUi\Items;

class Widget
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('admin-ui.unregister_widget', [])) :
            add_action('widget_init', [$this, 'widget_init']);
        endif;
    }

    /**
     * Initialisation des Widgets de wordpress.
     *
     * @return void
     */
    public function widget_init()
    {
        foreach (config('admin-ui.unregister_widget', []) as $widget) :
            switch ($widget) :
                default :
                    unregister_widget($widget);
                    break;
                case 'pages':
                case 'calendar':
                case 'archives':
                case 'links':
                case 'meta':
                case 'searc':
                case 'text':
                case 'categories':
                case 'recent posts':
                case 'recent comments':
                case 'rss':
                case 'tag cloud':
                case 'nav menu':
                    unregister_widget('WP_Widget_' . preg_replace('/\s/', '_', ucwords($widget)));
                    break;
                case 'rss' :
                    unregister_widget('WP_Widget_RSS');
                    break;
                case 'nav menu' :
                    unregister_widget('WP_Nav_Menu_Widget');
                    break;
            endswitch;
        endforeach;
    }
}