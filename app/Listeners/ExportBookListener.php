<?php

namespace App\Listeners;

use App\Events\BookUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class ExportBookListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BookUpdatedEvent  $event
     * @return void
     */
    public function handle(BookUpdatedEvent $event)
    {
        Cache::put('should_export', true, now()->addMinutes(10));
    }
}
