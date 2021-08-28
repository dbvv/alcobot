<?php

namespace App\Widgets;

use App\Models\Order;
use App\Models\TelegramUsers;
use SleepingOwl\Admin\Widgets\Widget;

class OrdersTotalWidget extends Widget {
    public function active() {
        return true;
    }

    public function position() {
        return 0;
    }

    public function toHtml() {
        $count = Order::count();
        $usersCount = TelegramUsers::count();
        return view('widgets.orders_total', compact('count', 'usersCount'))->render();
    }

    public function template()
    {
        // AdminTemplate::getViewPath('dashboard') == 'sleepingowl:default.dashboard'
        return \AdminTemplate::getViewPath('dashboard');
    }

    public function block()
    {
        return 'block.top';
    }

}
