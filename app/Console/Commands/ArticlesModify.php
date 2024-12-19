<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class ArticlesModify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:modify
                            {slug : Slug of the article to modify}
                            {--p|publish : Publish the article}
                            {--u|unpublish : Unpublish the article}
                            {--t|title= : Change the title}
                            {--s|slug= : Change the slug}
                            {--c|category= : Change the category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Modify an article\'s attributes';

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        $slug = $this->argument('slug');
        $publish = $this->option('publish');
        $unpublish = $this->option('unpublish');
        $title = $this->option('title');
        $new_slug = $this->option('slug');
        $category = $this->option('category');

        if ($publish and $unpublish) {
            $this->error('You cannot publish and unpublish at the same time Mr Shrödinger');
            return self::FAILURE;
        }

        if (!empty($category)) {
            $categories = Article::getCategories();
            if (!in_array($category, array_keys($categories))) {
                $this->error('Invalid category. Available categories are: ' . implode(', ', array_keys($categories)));
                return self::FAILURE;
            }
        }

        $article = Article::slug($slug)->first();

        if (!$article) {
            $this->error('Article not found with slug ' . $slug);
            return self::FAILURE;
        }

        if (!empty($new_slug) and Article::slug($new_slug)->exists()) {
            $this->error('Slug '.$new_slug.' already exists.');
            return self::FAILURE;
        }

        $feedback = [];

        if ($publish) {
            $article->published = true;
            $feedback[] = 'Article Published';
        }

        if ($unpublish) {
            $article->published = false;
            $feedback[] = 'Article Unpublished';
        }

        if (!empty($title)) {
            $article->title = $title;
            $feedback[] = 'Article title is now: ' . $title;
        }

        if (!empty($new_slug)) {
            $article->slug = $new_slug;
            $feedback[] = 'Article slug is now: ' . $new_slug;
        }

        if (!empty($category)) {
            $article->category = $category;
            $feedback[] = 'Article category is now: ' . $category;
        }

        $article->save();

        if (count($feedback)) {
            $this->getOutput()->block($feedback);
        }
        else {
            $this->info('Well that was easy. Nothing to do.');
        }


        return self::SUCCESS;
    }
}
