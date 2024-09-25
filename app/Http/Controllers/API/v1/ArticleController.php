<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'published_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'published_date' => Carbon::create($request->published_date)->toDateString(),
            ]);
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data store to db'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error storing data :'. $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed store data to db'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}