<?php

namespace App\Console\Commands;

use App\Repositories\DownloadsRepository;
use Illuminate\Console\Command;

class IndexDownloads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloads:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes downloads';

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
    public function handle(DownloadsRepository $downloadsRepository)
    {
        $downloads = $downloadsRepository->index()->map->info();
        $this->table(['ID', 'URL', 'STATUS', 'DOWNLOAD'], $downloads);
    }
}
