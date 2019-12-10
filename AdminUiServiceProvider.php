<?php

namespace tiFy\Plugins\AdminUi;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\AdminUi\Items\AdminBar;
use tiFy\Plugins\AdminUi\Items\AdminMenu;
use tiFy\Plugins\AdminUi\Items\Comment;
use tiFy\Plugins\AdminUi\Items\Dashboard;
use tiFy\Plugins\AdminUi\Items\Embed;
use tiFy\Plugins\AdminUi\Items\Emoji;
use tiFy\Plugins\AdminUi\Items\MetaTag;
use tiFy\Plugins\AdminUi\Items\PostType;
use tiFy\Plugins\AdminUi\Items\RestApi;
use tiFy\Plugins\AdminUi\Items\Taxonomy;
use tiFy\Plugins\AdminUi\Items\Widget;

class AdminUiServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'admin-ui'
    ];

    /**
     * @inheritdoc
     */
    public function boot()
    {
        add_action('after_setup_theme', function() {
            $this->getContainer()->get('admin-ui');

            $items = [
                AdminBar::class,
                AdminMenu::class,
                Comment::class,
                Dashboard::class,
                Embed::class,
                Emoji::class,
                MetaTag::class,
                PostType::class,
                RestApi::class,
                Taxonomy::class,
                Widget::class,
            ];

            foreach ($items as $concrete) :
                new $concrete();
            endforeach;
        });
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->getContainer()->share('admin-ui', function() {
            return new AdminUi();
        });
    }
}