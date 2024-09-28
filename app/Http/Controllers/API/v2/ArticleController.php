<?php

namespace App\Http\Controllers\API\v2;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{

    public function index(Request $request)
    {

        $query = Article::query()->latest('published_date');

        $keyword = $request->input('title');

        if($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%'); 
        }  

        $articles = $query->paginate(3);

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
