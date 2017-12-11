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

        foreach($request->input('keywords') as $keyword)
            $document->keywords()->attach(Keyword::firstOrCreate(['name' => $keyword]));

        $document->keywords()->searchable();

        $route = $document->parent == NULL ? 'index' : 'show';

        return redirect()->route('folders.' . $route, $document->parent)->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Dokument úspěšně nahrán.'
        ]);
    }

    /**
     * Display the specified document.
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
     * Display the form for editing the specified document.
     *
     * @param Document $document
     * @return $this
     */
    public function edit(Document $document)
    {
        if($document == NULL || !Auth::user()->canAccessDocument($document))
            abort(401);

        return view('documents.edit')->with([
            'document' => $document,
            'pageTitle' => $document->name,
        ]);
    }

    /**
     * Update the specified document in storage.
     *
     * @param Document $document
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Document $document, Request $request)
    {
        if($document == NULL || !Auth::user()->canAccessDocument($document))
            abort(401);

        $document->abstract = $request->input('abstract');
        $document->update();

        $document->keywords()->sync([]);

        foreach($request->input('keywords') as $keyword)
            $document->keywords()->attach(Keyword::firstOrCreate(['name' => $keyword]));

        $document->keywords()->searchable();

        return redirect()->route('documents.show', $document->slug)->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Dokument úspěšně upraven.'
        ]);
    }

    /**
     * Remove the specified document from storage.
     *
     * @param Document $document
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
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
        ]);
    }

    /**
     * Download the specified document.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function download(Document $document)
    {
        if($document != NULL && Auth::user()->canAccessDocument($document))
            return response()->download($document->getMedia()->first()->getPath());

        abort(404);
    }

    /**
     * Find the specified document by keywords.
     *
     * @param Request $request
     * @return $this
     */
    public function find(Request $request)
    {
        $documents = Document::whereHas('keywords', function($query) use ($request) {
            $query->whereIn('name', $request->input('keywords'));
        })->where('owner_id', Auth::id())->get();

        return view('documents.find')->with([
            'documents' => $documents,
            'pageTitle' => 'Vyhledávání podle klíčových slov'
        ]);
    }

    /**
     * Show the form for editing the specified document's sharing options.
     *
     * @param Document $document
     * @return $this
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

    /**
     * Update the sharing options for the specified document in storage.
     *
     * @param Request $request
     * @param Document $document
     * @return \Illuminate\Http\RedirectResponse
     */
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
