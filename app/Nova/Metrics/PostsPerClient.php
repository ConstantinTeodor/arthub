<?php

namespace App\Nova\Metrics;

use App\Models\Client;
use App\Models\Post;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class PostsPerClient extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param NovaRequest $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Post::class, 'client_id')
            ->label(function ($id) {
                return Client::find($id)->name;
            });
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return void
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'posts-per-client';
    }
}
