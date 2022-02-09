<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Response;
use SimpleXMLElement;

class SiteMapController extends Controller
{
    /**
     * Display a site map
     *
     * @return Response
     */
    public function index() : Response
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        $articles = Article::published()->notSite()->get();

        foreach( $articles as $article )
        {
            $url = $xml->addChild('url');
            $url->loc = url('/article/' . $article->slug);
            $url->lastmod = Date(DATE_ATOM, strtotime( $article->updated_at ) );
        }

        return response( $xml->asXML() );
    }
}
