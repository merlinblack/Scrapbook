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
    private bool $onlyPublished;
    public function __construct()
    {
        $this->onlyPublished = config('app.env') !== 'local';
    }
    public function index(Request $request): Response
    {
        $articles = Article::query()->notSite();

        if ($this->onlyPublished) {
            $articles->published();
        }

        if ($request->has('category'))
            $articles->category($request->get('category'));

        if ($request->get('newest', 'false') === 'true') {
            $articles->orderby('created_at','desc');
        }
        else {
            $articles->orderby('created_at','asc');
        }

        $articleList = $articles->get(['category','slug','title','published']);

        if (!$this->onlyPublished) {
            foreach ($articleList as $article) {
                if ($article->published === false) {
                    $article->title = $article->title . ' [Unpublished]';
                }
            }
        }

        return Inertia::render('Welcome', [
            'quip' => OneLiners::getOneLiner(),
            'articles' => $articleList,
            'categories' => Article::getCategories(),
            'currentCategory' => $request->get('category',false),
            'newest' => $request->get('newest', 'false') === 'true',
            'debug' => '',
        ]);
    }

    /**
     * @param Request $_ unused
     * @param $slug
     * @return Response
     * @throws ArticleNotFound
     */
    public function show(Request $_, $slug): Response
    {
        $article = Article::slug($slug);

        if ($this->onlyPublished) {
            $article->published();
        }

        $article = $article->first();

        if (!$article) {
            throw new ArticleNotFound();
        }

        if (!$article->published) {
            $article->title = $article->title . ' [Unpublished]';
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
