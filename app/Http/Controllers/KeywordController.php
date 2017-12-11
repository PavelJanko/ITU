<?php

namespace App\Http\Controllers;

use App\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    /**
     * Instantiate a new keyword controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return JSON formatted keywords.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if($request->ajax())
            $term = trim(($request->input('term')));

        if(empty($term))
            return response()->json([]);

        $keywords = Keyword::search($term)->get();

        $formatted_keywords = [];
        foreach ($keywords as $keyword)
            $formatted_keywords[] = ['id' => $keyword->name, 'text' => $keyword->name];

        return response()->json($formatted_keywords);
    }
}
