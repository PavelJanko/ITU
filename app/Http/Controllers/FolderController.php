<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Instantiate a new folder controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of files and folders that the user can access.
     * Also show the user groups he belongs to.
     *
     * @return @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Auth::user()->documents->where('parent_id', NULL);
        $folders = Auth::user()->folders->where('parent_id', NULL);

        return view('dashboard.index')->with([
            'documents' => $documents,
            'folders' => $folders,
            'pageTitle' => 'Mé soubory'
        ]);
    }

    /**
     * Display a listing of files and folders that the user can access.
     * Also show the user groups he belongs to.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function show(Folder $folder)
    {
        if(Auth::user()->id == $folder->owner->id) {
            $documents = $folder->documents;
            $folders = $folder->folders;
            $parentFolder = $folder;
        } else
            abort(401);

        return view('dashboard.index')->with([
            'documents' => $documents,
            'folders' => $folders,
            'pageTitle' => 'Mé soubory',
            'parentFolderId' => $folder->id
        ]);
    }

    /**
     * Store a newly created folder in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Folder::where('parent_id', $request->input('parent_id', NULL))->pluck('name')->contains($request->name))
            return back()->with([
                'statusType' => 'danger',
                'statusText' => 'V adresáři již existuje složka se <strong>stejným</strong> názvem.'
            ]);;

        $request->request->add(['owner_id' => Auth::user()->id]);

        $folder = Folder::create($request->only(['owner_id', 'parent_id', 'name']));

        $route = $folder->parent == NULL ? 'index' : 'show';

        return redirect()->route('folders.' . $route, $folder->parent)->with([
            'statusType' => 'success',
            'statusText' => 'Složka <strong>úspěšně</strong> vytvořena.'
        ]);;
    }

    /**
     * Update the specified folder in storage.
     *
     * @param Request $request
     * @param Folder $folder
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Folder $folder)
    {
        if(Folder::where('parent_id', $request->input('parent_id', NULL))->pluck('name')->contains($request->name))
            return back()->with([
                'statusType' => 'danger',
                'statusText' => 'V adresáři již existuje složka se <strong>stejným</strong> názvem.'
            ]);

        $folder->name = $request->name;
        $folder->update();

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusText' => 'Složka <strong>úspěšně</strong> přejmenována.'
        ]);
    }

    /**
     * Remove the specified folder and it's contents from storage.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Folder $folder)
    {
        if($folder != NULL && $folder->owner_id == Auth::user()->id) {
            $this->mediaDelete($folder);
            $folder->delete();
        } else
            abort(401);

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusText' => 'Složka a její obsah byl <strong>úspěšně</strong> odstraněn.'
        ]);;
    }

    /**
     * Recursive function for deleting the media
     * associated with the documents inside a folder.
     *
     * @param Folder $folder
     */
    public function mediaDelete(Folder $folder)
    {
        if($folder->folders->count())
            foreach($folder->folders as $folderRec)
                $this->mediaDelete($folderRec);

        foreach($folder->documents as $document)
            $document->delete();
    }
}
