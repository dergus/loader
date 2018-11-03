<?php


namespace App\Repositories;


use App\Jobs\ProcessDownload;
use App\Models\Download;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Eloquent\BaseRepository;

class DownloadsRepository extends BaseRepository
{
    public function model()
    {
        return Download::class;
    }

    public function index()
    {
        return $this->orderBy('id', 'desc')->all();
    }

    /**
     * add download task
     * @param string $url
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function add(string $url)
    {
        $model = $this->create([
            'url' => $url,
            'status' => Download::STATUS_PENDING
        ]);

        ProcessDownload::dispatch($model);

        return $model;
    }

    /**
     * download the file
     * @param Download $download
     * @return Download
     */
    public function download(Download $download)
    {
        $client = resolve(Client::class);
        $download->setStatusDownloading();
        $extension = pathinfo($download->url, PATHINFO_EXTENSION);
        $fileName = str_random(12) . '.' . ($extension ?: 'txt');
        try {
            $client->get($download->url, ['sink' => Storage::disk('public')->path($fileName)]);
        } catch (\Exception $e) {
            $download->setStatusError();
            return $download;
        }

        $download->setStatusComplete($fileName);

        return $download;
    }
}