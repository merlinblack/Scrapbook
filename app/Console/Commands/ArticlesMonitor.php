<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ArticlesMonitor extends Command
{
    private bool $stop_monitoring = false;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:monitor
                            {directory=Editor : Name of the directory to monitor}
                            {--init : Initialise directory with articles from DB}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor a directory of article files and update the database when they are modified';
    private string $edit_dir;

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        $this->edit_dir = $this->argument('directory');
        $init_dir = $this->option('init');

        if ($init_dir) {
            if (!$this->initDirectory()) {
                $this->error('Could not initialise directory: ' . $this->edit_dir);
                return self::FAILURE;
            }
        }

        $this->monitor();

        return self::SUCCESS;
    }

    private function initDirectory() : bool
    {
        if (!file_exists($this->edit_dir)) {
            if (!mkdir($this->edit_dir, 0755, true)) {
                return false;
            }
        }

        $articles = Article::all();

        foreach ($articles as $article) {
            $filename = $this->edit_dir . DIRECTORY_SEPARATOR . $article->slug . '.md';
            file_put_contents($filename, $article->html);
            touch($filename,$article->updated_at->timestamp);
        }

        $this->info("\nDirectory " . $this->edit_dir . ' initialised from database.');

        return true;
    }

    public function handleSignal() : void
    {
        $this->stop_monitoring = true;
    }

    private function monitor() : void
    {
        $out = $this->getOutput();

        pcntl_async_signals(true);
        pcntl_signal(SIGTERM, [$this, 'handleSignal']);
        pcntl_signal(SIGINT, [$this, 'handleSignal']);

        $fd = inotify_init();
        $wd = inotify_add_watch($fd, $this->edit_dir, IN_CLOSE_WRITE);

        $out->block([
            'Monitoring for article changes in: ' . $this->edit_dir,
            'Press Ctrl+C to exit',
        ]);

        while (!$this->stop_monitoring) {
            sleep(1);
            if (inotify_queue_len($fd) > 0) {
                $events = inotify_read($fd);
                foreach ($events as $event) {
                    if (Str::endswith($event['name'], '.md')) {
                        $this->updateArticle($event['name']);
                    }
                }
            }
        }

        inotify_rm_watch($fd, $wd);

        fclose($fd);

        $this->info("\nExiting");
    }

    private function updateArticle(string $filename) : void
    {
        $slug = substr($filename, 0, -3);
        $article = Article::slug($slug)->first();

        if (!$article) {
            $this->error('Article not found with the slug: ' . $slug);
            return;
        }

        $this->info(Carbon::now()->format('H:i:s') . ' - Updating article: ' . $slug);

        $article->html = file_get_contents($this->edit_dir . DIRECTORY_SEPARATOR . $filename);
        $article->save();
    }


}
