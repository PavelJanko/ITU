<?php

namespace App\Http\Controllers;

use App\Document;
use App\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        static $documentsPerPage = 9;

        // Determine whether a user is authenticated, and if he is, check which documents he can access
        if(Auth::check())
            $documents = Document::whereHas('groups', function ($query) {
                $query->where('id', Auth::id());
            })->orWhereDoesntHave('groups')->paginate($documentsPerPage);

        // If the user is not authenticated, only retrieve documents without any group assigned to them
        else
            $documents = Document::doesntHave('groups')->paginate($documentsPerPage);

        $keywords = Keyword::all();

        return view('documents.index', compact('documents', 'keywords'));
    }

    /**
     * Show the form for creating a new document.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified document.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        echo $document->name;
    }

    /**
     * Show the form for editing the specified document.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified document from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }
}
