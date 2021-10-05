<?php

namespace App\Announcements;

use App\Announcements\Actions\AnnouncementAction;
use App\Announcements\Actions\AnnouncementCrudAction;
use ClientX\Module;
use ClientX\Renderer\RendererInterface;
use ClientX\Router;
use ClientX\Theme\ThemeInterface;
use Psr\Container\ContainerInterface;

class AnnouncementsModule extends Module
{

    const MIGRATIONS = __DIR__ . '/db/migrations';
    const SEEDS = __DIR__ . '/db/seeds';
    const DEFINITIONS = __DIR__ . '/config.php';


    const TRANSLATIONS = [
        "fr_FR" => __DIR__ . "/trans/fr.php",
        "en_GB" => __DIR__ . "/trans/en.php"
    ];

    public function __construct(
        Router $router,
        RendererInterface $renderer,
        ThemeInterface $theme,
        ContainerInterface $container
    )
    {
        $adminPrefix = $container->get('admin.prefix');
        $router->get("/news", AnnouncementAction::class, 'announcements.index');
        $router->get("/news/[i:year]-[i:month]", AnnouncementAction::class, 'announcements.view');
        $router->get("/news/[i:id]", AnnouncementAction::class, 'announcements.show');
        if ($container->has("admin.prefix")) {
            $router->crud("$adminPrefix/announcements", AnnouncementCrudAction::class, 'announcements.crud');
            $renderer->addPath("announcements_admin", __DIR__ . '/Views');
        }
        $renderer->addPath("announcements", $theme->getViewsPath() . "/Announcements");
    }
}
