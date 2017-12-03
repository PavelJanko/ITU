<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
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

        $group = Group::create($request->only(['creator_id', 'name']));
        $group->members()->attach(Auth::id());

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Skupina byla úspěšně vytvořena.'
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
        $documents = $group->documents()->where('owner_id', '<>', Auth::id())->get()->sortBy('name');
        $folders = $group->folders()->where('owner_id', '<>', Auth::id())->get()->sortBy('name');
        $groups = Auth::user()->groups();

        return view('dashboard.index2')->with([
            'documents' => $documents,
            'folders' => $folders,
            'groups' => $groups,
            'pageTitle' => 'Sdílení skupiny ' . $group->name
        ]);
    }

    /**
     * Display the form for editing the specified group.
     *
     * @return $this
     */
    public function edit()
    {
        return view('groups.edit')->with([
            'groups' => Auth::user()->groups,
            'pageTitle' => 'Správa skupin'
        ]);
    }

    /**
     * Update the specified group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        if($group != NULL && $group->creator_id == Auth::id()) {
            $syncResult = $group->members()->sync($request->input('members'));

            foreach($syncResult['detached'] as $detached) {
                if($detached != $group->creator_id) {
                    $group->documents()->detach(User::find($detached)->documents->pluck('id'));
                    $group->folders()->detach(User::find($detached)->folders->pluck('id'));
                }
            }

            $group->members()->attach($group->creator_id);

            return redirect()->back()->with([
                'statusType' => 'success',
                'statusTitle' => 'Úspěch!',
                'statusText' => 'Seznam členů úspěšně upraven.'
            ]);
        }

        abort(404);
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
            abort(404);

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Skupina byla úspěšně odstraněna.'
        ]);
    }

    public function addMember(Request $request, Group $group)
    {
        if($group != NULL && $group->creator_id == Auth::id() && $request->email != Auth::user()->email) {
            $newMember = User::where('email', $request->input('email'))->first();

            if($newMember == NULL)
                return redirect()->back()->with([
                    'statusType' => 'error',
                    'statusTitle' => 'Jejda!',
                    'statusText' => 'Uživatel s touto e-mailovou adresou neexistuje.'
                ]);

            $group->members()->syncWithoutDetaching($newMember);

            return redirect()->back()->with([
                'statusType' => 'success',
                'statusTitle' => 'Úspěch!',
                'statusText' => 'Uživatel byl úspěšně přidán.'
            ]);
        }

        abort(401);
    }

    public function editMembers(Group $group)
    {
        if($group != NULL && $group->creator_id == Auth::id())
            return view('groups.edit-members')->with([
                'group' => $group,
                'pageTitle' => 'Upravení členů skupiny'
            ]);
        else
            abort(404);
    }

    /**
     * Detach a user from the specified group.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function leave(Group $group)
    {
        if($group != NULL && $group->members->pluck('id')->contains(Auth::id())) {
            $group->members()->detach(Auth::id());
            $group->load('members');

            if($group->creator_id == Auth::id()) {
                if ($group->members->count() > 0) {
                    $group->creator_id = $group->members->first()->id;
                    $group->documents()->detach(Auth::user()->documents->pluck('id'));
                    $group->folders()->detach(Auth::user()->folders->pluck('id'));
                    $group->update();
                } else
                    $group->delete();
            }
        } else
            abort(404);

        return redirect()->back()->with([
            'statusType' => 'success',
            'statusTitle' => 'Úspěch!',
            'statusText' => 'Skupina byla úspěšně opuštěna.'
        ]);
    }
}
