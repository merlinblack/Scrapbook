<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class ArticlesDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "articles:dump
                            {file=articles : base name of the output file. This will have '.tar.gz' added}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save all articles as json files in a gzipped tar archive.';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle(): int
    {
        $tarfile = $this->argument('file') . '.tar';

        if (file_exists($tarfile)) {
            $this->error("File: '{$tarfile}' exists already. Cowardly refusing to overwrite it.");
            return self::FAILURE;
        }

        if (file_exists($tarfile . '.gz')) {
            $this->error("File: '{$tarfile}.gz' exists already. Cowardly refusing to overwrite it.");
            return self::FAILURE;
        }

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

        $this->block('Wrote: ' . $tarfile . '.gz');

        return self::SUCCESS;
    }
}
