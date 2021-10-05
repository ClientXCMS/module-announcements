<?php

namespace App\Announcements\Navigation;

use ClientX\Navigation\NavigationItemInterface;
use ClientX\Renderer\RendererInterface;

class AnnouncementAdminItem implements NavigationItemInterface
{

    public function render(RendererInterface $renderer): string
    {
        return $renderer->render("@announcements_admin/menu");
    }

    public function getPosition(): int
    {
        return 70;
    }
}
