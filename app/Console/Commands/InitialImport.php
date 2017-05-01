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
    protected $signature = 'import:all {--offset=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all the bookmarks at once';

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
        $this->info("Starting import...");
        $updated = $this->pb->fullUpdate($this->option('offset'));
        $this->info("Import complete! " . $updated . " bookmarks imported.");
        $this->info("If you have more bookmarks than this please run this command again using --offset=" . $updated);
    }
}
