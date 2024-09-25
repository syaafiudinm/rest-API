<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function index()
    {

        $articles = Article::latest('published_date')->get();

        if($articles->isEmpty()) {
            return response()->json([
                'data' => Response::HTTP_NOT_FOUND,
                'message' => 'Article Empty'
            ], Response::HTTP_NOT_FOUND);
        }else {
            return response()->json([
                'data' => $articles->map(function ($article) {
                    return [
                        'title' => $article->title,
                        'content' => $article->content,
                        'published_date' => $article->published_date
                    ];
                }),
                'message' => 'list Article',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }

    }
}
