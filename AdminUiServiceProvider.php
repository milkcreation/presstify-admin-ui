<?php

namespace tiFy\Plugins\AdminUi;

use tiFy\App\Container\AppServiceProvider;
use tiFy\Plugins\AdminUi\AdminUi;
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

class AdminUiServiceProvider extends AppServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $singletons = [
        AdminUi::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->app->resolve(AdminUi::class);

        $this->app->appAddAction(
            'after_setup_theme',
            function() {
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

                foreach ($items as $abstract) :
                    $concrete = $this->getContainer()
                        ->singleton($abstract)
                        ->build([$this->app]);
                endforeach;
            }
        );
    }
}