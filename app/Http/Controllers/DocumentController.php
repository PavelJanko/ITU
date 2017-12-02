<?php

namespace App\Http\Controllers;

use App\Document;
use App\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Instantiate a new document controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            return back()->with([
                'statusType' => 'error',
                'statusTitle' => 'Jejda!',
                'statusText' => 'V adresáři již existuje dokument se stejným názvem.'
            ]);;

        $request->request->add(['owner_id' => Auth::id()]);

        $extension = $request->document->extension();
        $request->request->add(['extension' => $extension != NULL ? $extension : '?']);

        $document = Document::create($request->only(['owner_id', 'parent_id', 'name', 'extension', 'abstract']));
        $document->addMediaFromRequest('document')->toMediaCollection();

        $route = $document->parent == NULL ? 'index' : 'show';

        return redirect()->route('folders.' . $route, $document->parent)->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Dokument úspěšně nahrán.'
        ]);
    }

    /**
     * Display a listing of files and folders that the user can access.
     * Also show the user groups he belongs to.
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        if($document == NULL || !Auth::user()->canAccessDocument($document))
            abort(401);

        return view('documents.show')->with([
            'document' => $document,
            'pageTitle' => $document->name,
        ]);
    }
    
    /**
     * Remove the specified document from storage.
     *
     * @param Document $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Document $document)
    {
        if($document != NULL && $document->owner_id == Auth::id())
            $document->delete();
        else
            abort(401);
        
        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Dokument úspěšně odstraněn.'
        ]);;
    }

    /**
     * Display the specified document.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function download(Document $document)
    {
        if($document != NULL && $document->owner_id == Auth::id())
            return response()->download($document->getMedia()->first()->getPath());

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function sharingEdit(Document $document)
    {
        return view('folder.sharing-edit')->with([
            'item' => $document,
            'itemGroups' => $document->groups,
            'ownerGroups' => Auth::user()->groups,
            'pageTitle' => 'Upravení sdílení pro soubor',
            'type' => 'document'
        ]);
    }

    public function sharingUpdate(Request $request, Document $document)
    {
        $document->groups()->sync($request->input('groups'));

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Sdílení pro dokument úspěšně upraveno.'
        ]);
    }
}
