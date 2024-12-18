<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static published()
 * @method static notSite()
 * @method static category(string $category)
 * @method static slug(string $slug)
 */
class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'category',
        'html',
        'published',
        'created_at',
        'updated_at'
    ];

    static public function getCategories() : array
    {
        return [
            'türkçe' => 'Türkçe (Turkish Language)',
            'geek' => 'Programming',
            'poems' => 'Poems',
            'microcontroller' => 'Microcontrollers & Electronics',
        ];
    }

    static public function create($title, $slug, $category) : Article
    {
        $article = new Article();
        $article->fill([
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'published' => false,
            'html' => '',
        ]);
        $article->save();

        return $article;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function scopeNotSite(Builder $query): Builder
    {
        return $query->where('category', '!=', 'site');
    }

    public function scopeCategory(Builder $query, $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeSlug(Builder $query, $slug): Builder
    {
        return $query->where('slug',$slug);
    }
}
