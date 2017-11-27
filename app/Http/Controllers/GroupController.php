<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Instantiate a new group controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = collect();
        $folders = collect();

        Auth::user()->groups()->each(function($item) use ($documents) {
            $documents->push($item->documents);
        });

        Auth::user()->groups()->each(function($item) use ($folders) {
            $folders->push($item->folders);
        });


        $documents = $documents->collapse()->where('owner_id', '<>', Auth::id())->unique('id')->sortBy('name');
        $folders = $folders->collapse()->where('owner_id', '<>', Auth::id())->unique('id')->sortBy('name');
        $groups = Auth::user()->groups();

        return view('dashboard.index2')->with([
            'documents' => $documents,
            'folders' => $folders,
            'groups' => $groups,
            'pageTitle' => 'Skupiny a sdílení'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['creator_id' => Auth::id()]);

        Group::create($request->only(['creator_id', 'name']));

        return redirect()->back()->with([
           'statusType' => 'success',
           'statusText' => 'Skupina byla <strong>úspěšně</strong> vytvořena.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        if($group != NULL && $group->creator_id == Auth::id())
            $group->delete();
        else
            abort(401);

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusText' => 'Skupina byla <strong>úspěšně</strong> odstraněna.'
        ]);
    }
}
