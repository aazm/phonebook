<?php

namespace App\Console\Commands;

use App\Services\ExchangeServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BookExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phonebook:export {force?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export phonebook to csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(Cache::get('should_export') || $this->argument('force')) {

            Log::info('starting export');

            $lock = '/tmp/phonebookexport.pid';

            if(file_exists($lock)) {

                Log::warning('failed: lock file already exists');

                return;
            }

            $pid = getmypid();
            @file_put_contents($lock, $pid);

            if(!file_exists($lock)) {
                Log::warning('failed:lock file disappeared');
                return;
            }

            if($pid !== (int) file_get_contents($lock)) {

                Log::warning('failed: another process is running');

                return;
            }


            resolve(ExchangeServiceInterface::class)->export();

            @unlink($lock);

            Cache::forget('should_export');

            Log::warning('success');

        }
    }
}
