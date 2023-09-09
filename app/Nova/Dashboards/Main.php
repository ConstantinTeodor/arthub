<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\NewClients;
use App\Nova\Metrics\PostsPerClient;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new NewClients(),
            new PostsPerClient(),
            new Help,
        ];
    }
}
