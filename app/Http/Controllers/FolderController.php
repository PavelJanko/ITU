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
     * @param Folder $folder
     * @return @return \Illuminate\Http\Response
     */
    public function getContents(Folder $folder = NULL)
    {
        $documents = $folders = $parentFolder = NULL;

        if($folder == NULL) {
            $documents = Auth::user()->documents->where('parent_id', NULL);
            $folders = Auth::user()->folders->where('parent_id', NULL);;
        } elseif(Auth::user()->id == $folder->owner->id) {
            $documents = $folder->documents;
            $folders = $folder->folders;
            $parentFolder = $folder;
        } else
            abort(401);

        return view('partials.dashboard-table')->with([
            'documents' => $documents,
            'folders' => $folders,
            'parentFolder' => $parentFolder,
            'pageTitle' => 'Mé soubory'
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
        if($request->has('parent_id') && Folder::where('parent_id', $request->parent_id)->pluck('name')->contains($request->name))
            return back()->withInput();

        $request->request->add(['owner_id' => Auth::user()->id]);
        Folder::create($request->only(['owner_id', 'parent_id', 'name']));

        return redirect()->route('dashboard.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function show(Folder $folder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function edit(Folder $folder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $folder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        //
    }
}
