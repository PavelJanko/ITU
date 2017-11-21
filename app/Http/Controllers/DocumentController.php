<?php

namespace App\Http\Controllers;

use App\Document;
use App\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['name' => $request->document->getClientOriginalName()]);

        if(Document::where('parent_id', $request->input('parent_id', NULL))->pluck('name')->contains($request->name))
            return back()->withInput();

        $request->request->add(['owner_id' => Auth::user()->id]);

        $extension = $request->document->extension();
        $request->request->add(['extension' => $extension != NULL ? $extension : '?']);

        $document = Document::create($request->only(['owner_id', 'parent_id', 'name', 'extension', 'abstract']));
        $document->addMediaFromRequest('document')->toMediaCollection();

        $route = $document->parent == NULL ? 'index' : 'show';

        return redirect()->route('folder.' . $route, $document->parent);
    }

    /**
     * Display the specified document.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function download(Document $document)
    {
        if(Auth::user()->id == $document->owner_id)
            return response()->download($document->getMedia()->first()->getPath());

        abort(404);
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
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Document $document)
    {
        if($document != NULL && $document->owner_id == Auth::user()->id)
            $document->delete();
        else
            abort(401);
        
        return redirect()->back();
    }
}
