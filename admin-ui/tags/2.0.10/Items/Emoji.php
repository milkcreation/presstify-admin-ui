<?php

/**
 * Fonctionnalités :
 * - Désactivation de la prise en charge des emoji.
 */

namespace tiFy\Plugins\AdminUi\Items;

class Emoji
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('admin-ui.disable_emoji')) :
            add_filter('emoji_svg_url', '__return_false');
            add_action('init', [$this, 'init']);
            add_filter('tiny_mce_plugins', [$this, 'tiny_mce_plugins']);
        endif;
    }

    /**
     * Initialisation globale de Wordpress.
     *
     * @return null
     */
    public function init()
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }

    /**
     * Filtrage de la liste des plugins tinyMCE.
     *
     * @param array $plugins Liste des plugins tinyMCE actifs.
     *
     * @return array
     */
    public function tiny_mce_plugins($plugins)
    {
        if (is_array($plugins)) :
            return array_diff($plugins, ['wpemoji']);
        else :
            return [];
        endif;
    }
}