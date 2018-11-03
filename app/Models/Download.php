<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Download extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_DOWNLOADING = 1;
    const STATUS_COMPLETE = 2;
    const STATUS_ERROR = 3;

    const STATUS_STRING_MAP = [
        self::STATUS_PENDING => 'pending',
        self::STATUS_DOWNLOADING => 'downloading',
        self::STATUS_COMPLETE => 'complete',
        self::STATUS_ERROR => 'error'
    ];

    protected $fillable = [
        'url',
        'status',
        'storage_path'
    ];

    public function setStatusDownloading()
    {
        $this->update(['status' => self::STATUS_DOWNLOADING]);
    }

    public function setStatusError()
    {
        $this->update(['status' => self::STATUS_ERROR]);
    }

    public function setStatusComplete(string $path)
    {
        $this->update(['status' => self::STATUS_COMPLETE, 'storage_path' => $path]);
    }

    public function statusString()
    {
        return self::STATUS_STRING_MAP[$this->status];
    }

    public function downloadLink()
    {
        return $this->storage_path ? Storage::disk('public')->url($this->storage_path) : '';
    }

    public function info()
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'status' => $this->statusString(),
            'download_link' => $this->downloadLink()
        ];
    }
}