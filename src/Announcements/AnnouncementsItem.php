<?php


namespace App\Announcements;


use App\Announcements\Database\AnnouncementTable;
use ClientX\Renderer\RendererInterface;

class AnnouncementsItem implements \ClientX\Navigation\NavigationItemInterface
{

    private AnnouncementTable $table;

    public function __construct(AnnouncementTable $table)
    {
        $this->table = $table;
    }

    /**
     * @inheritDoc
     */
    public function getPosition(): int
    {
        return 100;
    }

    /**
     * @inheritDoc
     */
    public function render(RendererInterface $renderer): string
    {
        return $renderer->render('@announcements/menu', ['announcements' => $this->table->fetchPinned()]);
    }
}