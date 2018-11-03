<?php

namespace App\Jobs;

use App\Models\Download;
use App\Repositories\DownloadsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $download;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Download $download)
    {
        $this->download = $download;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DownloadsRepository $downloadsRepository)
    {
        $downloadsRepository->download($this->download);
    }
}
