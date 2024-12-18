<?php

namespace App\Http\Controllers;

use App\Exceptions\ArticleNotFound;
use App\Helpers\OneLiners;
use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Michelf\MarkdownExtra;

class ArticleController extends Controller
{
    public function index(Request $request): Response
    {
        $articles = Article::query()
            ->published()
            ->notSite()
            ->orderBy('created_at');

        if($request->has('category'))
            $articles->category($request->get('category'));

        $articles = $articles->get(['category','slug','title']);

        return Inertia::render('Welcome', [
            'quip' => OneLiners::getOneLiner(),
            'articles' => $articles,
            'categories' => Article::getCategories(),
        ]);
    }

    /**
     * @throws ArticleNotFound
     */
    public function show(Request $request, $slug): Response
    {
        $article = Article::query()->published()->slug($slug)->first();

        if (!$article) {
            throw new ArticleNotFound();
        }

        $html = $article->html;
        $html = str_replace('[[title]]', $article->title, $html);
        $html = MarkdownExtra::defaultTransform($html);

        $article->html = $html;

        return Inertia::render('Article', [
            'quip' => OneLiners::getOneLiner(),
            'article' => $article
        ]);
    }
}
