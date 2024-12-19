<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;

class ArticlesList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all articles';

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        $articles = Article::all();

        $list = [];

        foreach ($articles as $article) {
            $list[] = [
                $article->slug,
                $article->title,
                $article->published ? '✅' : '❌',
                $article->created_at->isoFormat('YY MMM DD HH:mm'),
                $article->updated_at->isoFormat('YY MMM DD HH:mm'),
            ];
        }

        $table = new Table($this->getOutput());
        $table->setHeaders(['Slug', 'Title', 'Pub', 'Created', 'Updated']);
        $table->setHeaderTitle('Articles');
        $table->setColumnMaxWidth(0,22);
        $table->setColumnMaxWidth(1,30);
        $table->setStyle('box');
        $table->setRows($list);
        $table->render();

        return self::SUCCESS;
    }
}
