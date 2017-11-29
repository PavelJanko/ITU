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
        $documents = $folder->documents;
        $folders = $folder->folders;

        if($folder == NULL || !Auth::user()->canAccessFolder($folder))
            abort(401);

        return view('dashboard.index')->with([
            'documents' => $documents,
            'folders' => $folders,
            'pageTitle' => 'Mé soubory',
            'parentFolder' => $folder
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
                'statusType' => 'error',
                'statusTitle' => 'Jejda!',
                'statusText' => 'V adresáři již existuje složka se stejným názvem.'
            ]);;

        $request->request->add(['owner_id' => Auth::id()]);

        $folder = Folder::create($request->only(['owner_id', 'parent_id', 'name']));

        $route = $folder->parent == NULL ? 'index' : 'show';

        return redirect()->route('folders.' . $route, $folder->parent)->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Složka úspěšně vytvořena.'
        ]);
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
                'statusType' => 'error',
                'statusTitle' => 'Jejda!',
                'statusText' => 'V adresáři již existuje složka se stejným názvem.'
            ]);

        $folder->name = $request->name;
        $folder->update();

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Složka úspěšně přejmenována.'
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
        if($folder != NULL && $folder->owner_id == Auth::id()) {
            $this->mediaDelete($folder);
            $folder->delete();
        } else
            abort(401);

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Složka i její obsah byl úspěšně odstraněn.'
        ]);
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

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function sharingEdit(Folder $folder)
    {
        return view('folder.sharing-edit')->with([
            'item' => $folder,
            'itemGroups' => $folder->groups,
            'ownerGroups' => Auth::user()->groups,
            'pageTitle' => 'Upravení sdílení pro složku',
            'type' => 'folder'
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     */
    public function sharingUpdate(Request $request, Folder $folder)
    {
        $folder->groups()->sync($request->input('groups'));

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Složka i její obsah úspěšně nasdílen.'
        ]);
    }
}
