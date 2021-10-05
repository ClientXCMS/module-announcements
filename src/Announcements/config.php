<?php

use App\Announcements\Actions\AnnouncementCrudAction;
use App\Announcements\Navigation\AnnouncementAdminItem;

use ClientX\Navigation\DefaultMainItem;
use function DI\add;
use function DI\get;

return [
    'navigation.main.items' => add(new DefaultMainItem([DefaultMainItem::makeItem("announcements", "announcements.index", "fas fa-bullhorn")], 40)),
    'admin.menu.items' => add(get(AnnouncementAdminItem::class)),
    'homeoffline.items' => add(get(\App\Announcements\AnnouncementsItem::class)),

    'navigation.account.sidebar.items' => add(get(\App\Announcements\AnnouncementsItem::class)),
    AnnouncementCrudAction::class => \DI\autowire()->constructorParameter('driver', get('upload.driver'))
];
