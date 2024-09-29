<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Resources\ArticleCollection;
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
            return new ArticleCollection($articles);
        }

    }
}
