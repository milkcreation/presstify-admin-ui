<?php

/**
 * Fonctionnalités :
 * - Désactivation de la prise en charge d'intégration de contenu provenant de services tiers.
 *
 * Ressources :
 * @see https://codex.wordpress.org/Embeds
 * @see https://kinsta.com/knowledgebase/disable-embeds-wordpress/#inline-embed-js
 */

namespace tiFy\Plugins\AdminUi\Items;

class Embed
{
    /**
     * Liste des options de désactivation des éléments de l'embed.
     * @var array
     */
    protected $attributes = [
        'register_route'    => true,
        'discover'          => true,
        'filter_result'     => true,
        'discovery_links'   => true,
        'host_js'           => true,
        'tiny_mce_plugin'   => true,
        'pre_oembed_result' => true,
        'rewrite_rules'     => true,
        'dequeue_script'    => true
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if ($disable_embed = config('admin-ui.disable_embed', [])) :
            $this->attributes = ($disable_embed === true)
                ? $this->attributes
                : array_merge(
                    $this->attributes,
                    $disable_embed
                );

            add_action('init', [$this, 'init'], 9999);
        endif;
    }

    /**
     * Initialisation globale de Wordpress.
     *
     * @return null
     */
    public function init()
    {
        // Remove the REST API endpoint.
        if ($this->attributes['register_route']) :
            remove_action('rest_api_init', 'wp_oembed_register_route');
        endif;

        // Turn off oEmbed auto discovery.
        if ($this->attributes['discover']) :
            add_filter('embed_oembed_discover', '__return_false');
        endif;

        // Don't filter oEmbed results.
        if ($this->attributes['filter_result']) :
            remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        endif;

        // Remove oEmbed discovery links.
        if ($this->attributes['discovery_links']) :
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
        endif;

        // Remove oEmbed-specific JavaScript from the front-end and back-end.
        if ($this->attributes['host_js']) :
            remove_action('wp_head', 'wp_oembed_add_host_js');
        endif;
        if ($this->attributes['tiny_mce_plugin']) :
            add_filter('tiny_mce_plugins', [$this, 'tiny_mce_plugins']);
        endif;

        // Remove filter of the oEmbed result before any HTTP requests are made.
        if ($this->attributes['pre_oembed_result']) :
            remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
        endif;

        // Retire les régles de réécriture.
        if ($this->attributes['rewrite_rules']) :
            add_filter('rewrite_rules_array', [$this, 'rewrite_rules_array']);
        endif;

        // Retire le script d'intégration de la file.
        if ($this->attributes['dequeue_script']) :
            add_action('wp_footer', [$this, 'wp_footer']);
        endif;
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
        return array_diff($plugins, ['wpembed']);
    }

    /**
     * Filtage de la liste des règles de réécriture.
     *
     * @param array $rules Liste des règles de réécriture.
     *
     * @return array
     */
    public function rewrite_rules_array($rules)
    {
        foreach ($rules as $rule => $rewrite) :
            if (false !== strpos($rewrite, 'embed=true')) :
                unset($rules[$rule]);
            endif;
        endforeach;

        return $rules;
    }

    /**
     * Gestion des éléments du pied de page.
     *
     * @return void
     */
    public function wp_footer()
    {
        wp_dequeue_script('wp-embed');
    }
}