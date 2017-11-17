<?php

namespace App\Http\Controllers;

use App\Document;
use App\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Instantiate a new dashboard controller instance.
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
     * @return @return \Illuminate\Http\Response
     */
    public function folder(Folder $folder = NULL)
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
