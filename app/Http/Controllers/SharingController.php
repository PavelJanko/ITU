<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SharingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     */
    public function edit($id)
    {
        dd($id);
    }

    public function withGroups(Request $request, $id)
    {
        dd($request->all());
//        if($folder != NULL && $folder->owner->id == Auth::id()) {
//            dd($request->all());
//        } else
//            abort(401);
    }
}
