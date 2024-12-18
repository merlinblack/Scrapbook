<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use function PHPUnit\Framework\fileExists;

class ArticlesCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:create 
                            {title : The title of the article}
                            {slug : The slug of the article}
                            {category : The name of the article\'s category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new article';

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        $title = $this->argument('title');
        $slug = $this->argument('slug');
        $category = $this->argument('category');

        $categories = Article::getCategories();

        if (!in_array($category, array_keys($categories))) {
            $this->error('Invalid category. Available categories are: ' . implode(', ', array_keys($categories)));
            return self::FAILURE;
        }

        $this->info('Creating article: '.$title.' / '.$slug.' in '.$category);

        Article::create($title, $slug, $category);

        return self::SUCCESS;
    }
}
