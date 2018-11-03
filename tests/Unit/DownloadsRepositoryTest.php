<?php


namespace Tests\Unit;


use App\Jobs\ProcessDownload;
use App\Models\Download;
use App\Repositories\DownloadsRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DownloadsRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @var DownloadsRepository */
    protected $downloadsRepo;

    protected function setUp()
    {
        parent::setUp();
        $this->downloadsRepo = resolve(DownloadsRepository::class);
    }

    public function testIndex()
    {
        /** @var Collection $downloads */
        $downloads = factory(Download::class, 10)->create();
        $result = $this->downloadsRepo->index();
        $this->assertEquals($downloads->sortByDesc('id')->values()->toArray(), $result->toArray());
    }

    public function testAdd()
    {
        Queue::fake();
        $url = 'http://example.com/cat.gif';
        $d = $this->downloadsRepo->add($url);
        $this->assertDatabaseHas('downloads', ['url' => $url, 'status' => Download::STATUS_PENDING]);
        Queue::assertPushed(ProcessDownload::class, function ($job) use($d) {
            return $job->download->id == $d->id;
        });
    }

    public function testDownload()
    {
        $download = factory(Download::class)->create(['status' => Download::STATUS_PENDING, 'storage_path' => '']);
        $mock = new MockHandler([
            new Response(200, [], 'hello there'),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $this->app->singleton(Client::class, function () use($client) {
            return $client;
        });
        $this->downloadsRepo->download($download);
        $download = $download->fresh();
        $this->assertEquals(Download::STATUS_COMPLETE, $download->status);
        $this->assertNotEmpty($download->storage_path);
    }

    public function testDownloadError()
    {
        $download = factory(Download::class)->create(['status' => Download::STATUS_PENDING, 'storage_path' => '']);
        $mock = new MockHandler([
            new Response(404),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $this->app->singleton(Client::class, function () use($client) {
            return $client;
        });
        $this->downloadsRepo->download($download);
        $download = $download->fresh();
        $this->assertEquals(Download::STATUS_ERROR, $download->status);
        $this->assertEmpty($download->storage_path);
    }
}