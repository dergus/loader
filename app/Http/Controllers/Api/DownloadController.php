<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Download\Store;
use App\Repositories\DownloadsRepository;

class DownloadController extends Controller
{
    protected $downloadsRepo;

    public function __construct(DownloadsRepository $downloadsRepo)
    {
        $this->downloadsRepo = $downloadsRepo;
    }

    public function index()
    {
        $downloads = $this->downloadsRepo->index();
        return $downloads->map->info();
    }

    public function store(Store $request)
    {
        $this->downloadsRepo->add($request->input('url'));
        return null;
    }
}