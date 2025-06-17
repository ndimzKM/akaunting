<?php

namespace Modules\Inventory\Listeners;

use App\Events\Menu\AdminCreated as Event;

class AddToMenu
{
    public function handle(Event $event)
    {
        $event->menu->dropdown('Inventory', function ($sub) {
            $sub->route('items.index', 'Items', 20, ['icon' => '']);
            $sub->route('inventory.units.index', 'Units', null, ['icon' => '']);
        }, 20, ['title' => 'Inventory', 'icon' => 'payments']);
    }
}
