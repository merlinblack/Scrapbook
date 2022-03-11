<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class articles_dump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "articles:dump {file=archive : base name of the output file. This will have '.tar.gz' added}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save all articles as json files in a gzipped tar archive.';

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
     * @return int
     */
    public function handle()
    {
        $tarfile = $this->argument('file') . '.tar';

        $archive = new \PharData($tarfile);
        $articles = Article::all();

        foreach ($articles as $article) {
            $json = json_encode($article, JSON_PRETTY_PRINT);
            $archive->addFromString('articles/' . $article->slug . '.json', $json);
        }

        /** Create .tar.gz from .tar */
        $archive->compress(\Phar::GZ);

        /** Remove .tar */
        unlink($tarfile);

        $this->getOutput()->block('Wrote: ' . $tarfile . '.gz');

        return Command::SUCCESS;
    }
}
