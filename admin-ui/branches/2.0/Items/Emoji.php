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
            add_action('init', [$this, 'init']);
            add_filter('tiny_mce_plugins', [$this, 'tiny_mce_plugins']);
            add_filter('wp_resource_hints', [$this, 'wp_resource_hints'], 10, 2);
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

    /**
     * Récupération de ressources pré-affichées dans le navigateur.
     *
     * @param array $urls Liste des urls des ressources pré-affichées.
     * @param string $relation_type Type de relation des urls à pré-afficher.
     *
     * @return array
     */
    public function wp_resource_hints($urls, $relation_type)
    {
        if ('dns-prefetch' == $relation_type) :
            /** This filter is documented in wp-includes/formatting.php */
            $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2.4/svg/');

            $urls = array_diff($urls, [$emoji_svg_url]);
        endif;

        return $urls;
    }
}