<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PinboardController;

class IncrementalImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:recent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches new bookmarks since the last update';

    /**
     * Pinboard controller object
     */
    protected $pb;

    /**
     * Create a new command instance.
     *
     * @return void
     */
     public function __construct()
     {
         parent::__construct();
         $this->pb = new PinboardController();
     }

     /**
      * Execute the console command.
      *
      * @return mixed
      */
     public function handle()
     {
         $this->info("Starting update...");
         $updated = $this->pb->incrementalUpdate();
         $this->info("Update complete! " . $updated . " new bookmarks.");
     }
}
