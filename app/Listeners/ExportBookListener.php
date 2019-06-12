<?php

namespace App\Listeners;

use App\Events\BookUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        //
    }
}
