<?php


namespace App\Http\Controllers;


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
        return view('downloads.index', compact('downloads'));
    }

    public function create()
    {
        return view('downloads.create');
    }

    public function store(Store $request)
    {
        $this->downloadsRepo->add($request->input('url'));
        return redirect('/downloads');
    }
}