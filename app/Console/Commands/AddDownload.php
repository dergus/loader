<?php

namespace App\Console\Commands;

use App\Repositories\DownloadsRepository;
use Illuminate\Console\Command;

class AddDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloads:add {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds download task';

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
        $downloadsRepository->add($this->argument('url'));
    }
}
