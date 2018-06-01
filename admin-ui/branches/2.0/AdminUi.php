<?php

/**
 * @name AdminUi
 * @desc Extension PresstiFy de gestion l'interface d'administration de Wordpress.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstiFy
 * @namespace \tiFy\Plugins\AdminUi
 * @version 2.0.0
 */

namespace tiFy\Plugins\AdminUi;

use tiFy\Apps\AppController;

final class AdminUi extends AppController
{
    /**
     * Liste des attributs de configuration
     * @var array {
     *
     *      @var string $admin_bar_menu_logo Personnalisation de l'entrée de menu logo de l'adminbar.
     *      @var array $remove_admin_bar_menu Nettoyage des entrées de menu de la barre d'administration.
     *          @internal ex. ['wp_logo', 'about', 'wporg', 'documentation', 'support-forums', 'feedback', 'site-name', 'view-site', 'updates', 'comments', 'new-content', 'my-account', 'disable_post', 'disable_post_category', 'disable_post_tag', 'disable_comment']
     *      @var string $admin_footer_text Personnalisation du texte  du pied de page de l'interface d'administration.
     *      @var array $remove_menu Nettoyage des entrées de menu de l'interface d'administration.
     *          @internal ex: ['dashboard', 'posts', 'media', 'pages', 'comments', 'appearence', 'plugins', 'users', 'tools', 'settings'].
     *          @internal ex. alt: ['index.php', 'edit.php', 'upload.php', 'edit.php?post_type=page', 'edit-comments.php', 'themes.php', 'plugins.php', 'users.php', 'tools.php', 'options-general.php']
     *      @var array $unregister_widget Nettoyage des entrées de menu de l'interface d'administration.
     *          @internal ex. [ 'pages', 'calendar', 'archives', 'links', 'meta', 'search', 'text', 'categories', 'recent posts', 'recent comments', 'rss', 'tag cloud', 'nav menu' ]
     *      @var array $remove_support_post Suppression de support des propriétés d'articles.
     *          @internal ex. ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats']
     *      @var array $remove_support_page Suppression de support des propriétés de page.
     *          @internal ex. ['title', 'editor', 'author', 'thumbnail', 'page-attributes', 'custom-fields', 'comments', 'revisions']
     *      @var array $remove_dashboard_meta_box Suppression des boîtes de saisie du tableau de bord.
     *          @internal ex. ['recent_comments', 'incoming_links', 'plugins', 'recent_drafts', 'activity', 'primary', 'secondary']
     * }
     */
    protected $attributes = [
        'admin_bar_menu_logo'       => '',
        'remove_admin_bar_menu'     => [],
        'admin_footer_text'         => '',
        'remove_menu'               => [],
        'unregister_widget'         => [],
        'remove_support_post'       => [],
        'remove_support_page'       => [],
        'remove_dashboard_meta_box' => [],
        'remove_meta_box_post'      => [],
        'remove_meta_box_page'      => [],
        'disable_comment'           => false,
        'disable_post_category'     => false,
        'disable_post_tag'          => false,
        'disable_post'              => false,
        'disable_emoji'             => false,
        'disable_embed'             => false,
        'disable_meta_tag'          => false,
        'disable_rest_api'          => false

        ## Suppression de support des propriétés d'un type de post personnalisé (%%custom_post_type%%)
        ## ex: remove_support_%%custom_post_type%% : [ 'title', 'editor' ]

        #-------------------------------------------------------------------------------------------------------
        # Suppression des boîtes de saisie
        ##
        ##

        ## Suppression des boîtes de saisie des articles
        ## ex: { authordiv: 'normal', categorydiv: 'side', commentstatusdiv: 'normal', commentsdiv: 'normal', formatdiv: 'normal', postcustom: 'normal', postexcerpt: 'normal', postimagediv: 'normal', revisionsdiv: 'normal', slugdiv: 'normal', submitdiv: 'side', tagsdiv-post_tag: 'side', trackbacksdiv: 'normal' }

        ## Suppression des boîtes de saisie des pages
        ## ex: { authordiv: 'normal', commentstatusdiv: 'normal', commentsdiv: 'normal', pageparentdiv: 'side', postcustom: 'normal', postimagediv: 'normal', revisionsdiv: 'normal', slugdiv: 'normal', submitdiv: 'side' }

        ## Suppression des boîtes de saisie d'un type de post personnalisé (%%custom_post_type%%)
        # ex: { authordiv: 'normal', commentstatusdiv: 'normal', commentsdiv: 'normal', pageparentdiv: 'side', postcustom: 'normal', postimagediv: 'normal', revisionsdiv: 'normal', slugdiv: 'normal', submitdiv: 'side' }
        # remove_meta_box_%%custom_post_type%% :

        #-------------------------------------------------------------------------------------------------------
        # Désactivation des interfaces commentaire

        #-------------------------------------------------------------------------------------------------------
        # Désactivation des catégories d'articles

        #-------------------------------------------------------------------------------------------------------
        # Désactivation des etiquettes (mot-clefs) d'articles

        #-------------------------------------------------------------------------------------------------------
        # Désactivation de taxonomy pour un type de post personnalisé
        #unregister_taxonomy_for_%%custom_post_type%% : [ 'taxonomy1', 'taxonomy2' ]

        #-------------------------------------------------------------------------------------------------------
        # Désactivation des articles

        #-------------------------------------------------------------------------------------------------------
        # Désactivation des emojis

        #-------------------------------------------------------------------------------------------------------
        # Désactivation de l'embed (Pise en charge de l'intégration de contenus de service tiers)
        # @see https://codex.wordpress.org/Embeds

        #-------------------------------------------------------------------------------------------------------
        # Mise à jour automatique du coeur de Wordpress
        # true (defaut tous ) | 'all' | [ 'dev', 'minor', 'major' ]
        # dev   : autorise la montée en version de développement (ex : 3.7-alpha-25000 -> 3.7-alpha-25678 -> 3.7-beta1 -> 3.7-beta2)
        # minor : autorise la montée en sous version (ex : 3.7.0 -> 3.7.1 -> 3.7.2 -> 3.7.4)
        # major : autorise la montée en version (ex : 3.7.0 -> 3.8.0 -> 3.9.1 )
        # @todo
        //auto_update_core:             true

        # Envoi d'un mail d'alerte à l'administrateur
        # @todo
        //auto_core_update_send_email:  true

        #-------------------------------------------------------------------------------------------------------
        # Mise à jour automatique des plugins de Wordpress
        # @todo
        //auto_update_plugin:           true

        #-------------------------------------------------------------------------------------------------------
        # Mise à jour automatique des themes de Wordpress
        # @todo
        //auto_update_theme:            true

        #-------------------------------------------------------------------------------------------------------
        # Mise à jour automatique des themes de Wordpress
        # @todo
        //auto_update_translation:      true
    ];

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function appBoot()
    {
        $this->appSet(
            'config',
            array_merge(
                $this->attributes,
                $this->appConfig()
            )
        );

        $this->appAddAction('init', null, 99);
        $this->appAddAction('widgets_init');
        $this->appAddAction('admin_menu', null, 99);
        $this->appAddAction('add_meta_boxes', null, 99);

        $this->appAddAction('wp_before_admin_bar_render');

        if ($this->appConfig('admin_bar_menu_logo')) :
            $this->appAddAction('admin_bar_menu', null, 11);
        endif;

        if ($this->appConfig('admin_footer_text')) :
            $this->appAddAction('admin_footer_text');
        endif;

        if ($this->appConfig('disable_post')) :
            $this->appAddAction('admin_init', 'disable_post_dashboard_meta_box');
            $this->appAddAction('admin_menu', 'disable_post_remove_menu');
            $this->appAddFilter('nav_menu_meta_box_object', 'disable_post_nav_menu_meta_box_object');
            $this->appAddAction('wp_before_admin_bar_render', 'disable_post_wp_before_admin_bar_render');
        endif;

        if ($this->appConfig('disable_comment')) :
            $this->appAddAction('init', 'disable_comment_init');
            $this->appAddAction('wp_widgets_init', 'disable_comment_wp_widgets_init');
            $this->appAddAction('admin_menu', 'disable_comment_remove_menu');
            $this->appAddAction('wp_before_admin_bar_render', 'disable_comment_wp_before_admin_bar_render');
        endif;

        if ($this->appConfig('disable_post_category')) :
            $this->appAddAction('init', 'disable_post_category');
        endif;

        if ($this->appConfig('disable_post_tag')) :
            $this->appAddAction('init', 'disable_post_tag');
        endif;

        if ($this->appConfig('disable_emoji')) :
            new Emoji();
        endif;

        if ($embed_opts = $this->appConfig('disable_embed')) :
            new Embed($embed_opts);
        endif;

        if ($this->appConfig('disable_meta_tag')) :
            new MetaTag();
        endif;

        if ($this->appConfig('disable_rest_api')) :
            new RestApi();
        endif;
    }

    /**
     * Inititalisation globale de Wordpress.
     *
     * @return void
     */
    public function init()
    {
        $this->remove_support_post_type();
        $this->unregister_taxonomy_for_object_type();
    }

    /**
     * Initialisation des widgets.
     *
     * @return void
     */
    public function widgets_init()
    {
        $this->unregister_widget();
    }

    /**
     * Initialisation du menu d'administration.
     *
     * @return void
     */
    public function admin_menu()
    {
        $this->remove_menu();
        $this->remove_dashboard_meta_box();
    }

    /**
     * Ajout de métaboxes.
     *
     * @return void
     */
    public function add_meta_boxes()
    {
        $this->remove_meta_box_post_types();
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
        $wp_admin_bar->remove_menu('wp-logo');

        foreach ($this->appConfig('admin_bar_menu_logo') as $node) :
            if (!empty($node['group'])) :
                $wp_admin_bar->add_group($node);
            else :
                $wp_admin_bar->add_menu($node);
            endif;
        endforeach;
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
        return $text = $this->appConfig('admin_footer_text');
    }

    /**
     * Pré-rendu de la barre d'administration.
     *
     * @return void
     */
    public function wp_before_admin_bar_render()
    {
        $this->remove_admin_bar_menu();
    }

    /**
     * Suppression des entrées de menus.
     *
     * @return void
     */
    private function remove_menu()
    {
        foreach ($this->appConfig('remove_menu', []) as $menu) :
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
                    remove_menu_page('edit-comments.php');
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

    /**
     * Suppression des attributs de support des types de post.
     *
     * @return void
     */
    private function remove_support_post_type()
    {
        foreach (array_keys($this->appConfig()) as $config) :
            if (!preg_match('/^remove_support_(.*)/', $config, $post_type)) :
                continue;
            endif;
            if (!post_type_exists($post_type[1])) :
                continue;
            endif;

            foreach ($this->appConfig($config, []) as $support) :
                remove_post_type_support($post_type[1], $support);
            endforeach;
        endforeach;
    }

    /**
     * Suppression des Widgets.
     *
     * @return void
     */
    private function unregister_widget()
    {
        foreach ($this->appConfig('unregister_widget', []) as $widget) :
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

    /**
     * Suppression de metaboxes du tableau de bord.
     *
     * @return void
     */
    private function remove_dashboard_meta_box()
    {
        foreach ($this->appConfig('remove_dashboard_meta_box', []) as $metabox => $context) :
            if (is_numeric($metabox)) :
                remove_meta_box('dashboard_' . $context, 'dashboard', false);
            elseif (is_string($metabox)) :
                remove_meta_box('dashboard_' . $metabox, 'dashboard', $context);
            endif;
        endforeach;
    }

    /**
     * Suppression de metaboxes par type de post.
     *
     * @return void
     */
    private function remove_meta_box_post_types()
    {
        foreach (array_keys($this->appConfig()) as $config) :
            if (!preg_match('/^remove_meta_box_(.*)/', $config, $post_type)) :
                continue;
            endif;
            if (!post_type_exists($post_type[1])) :
                continue;
            endif;

            $post_type = $post_type[1];

            foreach ($this->appConfig($config, []) as $metabox => $context) :
                if (is_numeric($metabox)) :
                    $_metabox = $context;
                    remove_meta_box($context, $post_type, false);
                elseif (is_string($metabox)) :
                    $_metabox = $metabox;
                    remove_meta_box($metabox, $post_type, $context);
                endif;

                // Hack Wordpress : Maintient du support de la modification du permalien
                if ($_metabox === 'slugdiv') :
                    add_action('edit_form_before_permalink', function ($post) use ($post_type) {
                        if ($post->post_type !== $post_type) :
                            return;
                        endif;
                        $editable_slug = apply_filters('editable_slug', $post->post_name, $post);
                        echo "<input name=\"post_name\" type=\"hidden\" size=\"13\" id=\"post_name\" value=\"" . esc_attr($editable_slug) . "\" autocomplete=\"off\" />";
                    });
                endif;
            endforeach;
        endforeach;
    }

    /**
     * Suppression d'entrée de menu de la barre d'administration.
     *
     * @return void
     */
    private function remove_admin_bar_menu()
    {
        global $wp_admin_bar;

        foreach ($this->appConfig('remove_admin_bar_menu', []) as $admin_bar_node) :
            $wp_admin_bar->remove_node($admin_bar_node);
        endforeach;
    }

    /**
     * Désactivation des interfaces relatives au type post
     */
    /**
     * Suppression de la metaboxe de tableau de bord - édition rapide.
     *
     * @return void
     */
    public function disable_post_dashboard_meta_box()
    {
        remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');
    }

    /**
     * Suppression du menu d'édition.
     *
     * @return void
     */
    public function disable_post_remove_menu()
    {
        remove_menu_page('edit.php');
    }

    /**
     * Désactivation de la metaboxe de menu
     * @param string $post_type
     *
     * @return boolean|string
     */
    public function disable_post_nav_menu_meta_box_object($post_type)
    {
        if ($post_type->name === 'post') :
            return false;
        else :
            return $post_type;
        endif;
    }

    /**
     * Désactivation de l'entrée de menu "nouveau" de la barre d'administration
     */
    public function disable_post_wp_before_admin_bar_render()
    {
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('new-post');
    }

    /**
     * Désactivation des interfaces relatives aux commentaires
     */
    /**
     * Désactivation du support des commentaires.
     *
     * @return void
     */
    public function disable_comment_init()
    {
        remove_post_type_support('post', 'comments');
        remove_post_type_support('page', 'comments');
        update_option('default_comment_status', 0);
    }

    /**
     * Désactivation des widgets.
     *
     * @return void
     */
    public function disable_comment_wp_widgets_init()
    {
        unregister_widget('WP_Widget_Recent_Comments');
    }

    /**
     * Désactivation des entrées de menu principal.
     *
     * @return void
     */
    public function disable_comment_remove_menu()
    {
        remove_menu_page('edit-comments.php');
        remove_submenu_page('options-general.php', 'options-discussion.php');
    }

    /**
     * Désactivation des entrées de la barre d'administration.
     *
     * @return void
     */
    public function disable_comment_wp_before_admin_bar_render()
    {
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('comments');

        if (is_multisite()) :
            foreach (get_sites() as $site) :
                $wp_admin_bar->remove_menu('blog-' . $site->blog_id . '-c');
            endforeach;
        endif;
    }

    /**
     * Taxonomies
     */
    /**
     * Désactivation des catégories du type post.
     *
     * @return void
     */
    public function disable_post_category()
    {
        global $wp_taxonomies;

        if (isset($wp_taxonomies['category'])) :
            $wp_taxonomies['category']->show_in_nav_menus = false;
        endif;

        unregister_taxonomy_for_object_type('category', 'post');
    }

    /**
     * Désactivation des mots clefs (etiquettes) du type post.
     *
     * @return void
     */
    public function disable_post_tag()
    {
        global $wp_taxonomies;

        if (isset($wp_taxonomies['post_tag'])) :
            $wp_taxonomies['post_tag']->show_in_nav_menus = false;
        endif;

        unregister_taxonomy_for_object_type('post_tag', 'post');
    }

    /**
     * Désactivation des taxonomie pour un type de post.
     *
     * @return void
     */
    public function unregister_taxonomy_for_object_type()
    {
        global $wp_taxonomies;

        foreach (array_keys($this->appConfig()) as $config) :
            if (!preg_match('/^unregister_taxonomy_for_(.*)/', $config, $post_type)) :
                continue;
            endif;
            if (!post_type_exists($post_type[1])) :
                continue;
            endif;

            foreach ($this->appConfig($config, []) as $taxonomy) :
                unregister_taxonomy_for_object_type($taxonomy, $post_type[1]);
            endforeach;
        endforeach;
    }
}
