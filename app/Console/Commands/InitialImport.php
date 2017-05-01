<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PinboardController;

class InitialImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all the bookmarks at once';

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
        $this->info("Starting import...");
        $this->pb->getAllRecords();
        $this->info("Import complete!");
    }
}
