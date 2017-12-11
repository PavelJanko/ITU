<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Instantiate a new comment controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $document = Document::find($request->input('document_id'));
        $request->request->add(['author_id' => Auth::id()]);

        if($document != NULL && Auth::user()->canAccessDocument($document))
            Comment::create($request->only(['author_id', 'document_id', 'body']));
        else
            abort(404);

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Komentář úspěšně přidán.'
        ]);
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Comment $comment)
    {
        if($comment != NULL && ($comment->author_id == Auth::id() || $comment->document->owner_id == Auth::id()))
            $comment->delete();
        else
            abort(404);

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Komentář úspěšně odstraněn.'
        ]);
    }
}
