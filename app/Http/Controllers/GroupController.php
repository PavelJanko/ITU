<?php

namespace App\Http\Controllers;

use App\Document;
use App\Folder;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Display a listing of the groups and shared documents and folders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::whereHas('groups.members', function($query) {
            $query->where('id', Auth::id());
        })->where('owner_id', '<>', Auth::id())->get();

        $folders = Folder::whereHas('groups.members', function($query) {
            $query->where('id', Auth::id());
        })->where('owner_id', '<>', Auth::id())->get();

        $groups = Auth::user()->groups();

        return view('groups.sharing')->with([
            'documents' => $documents,
            'folders' => $folders,
            'groups' => $groups,
            'pageTitle' => 'Skupiny a sdílení'
        ]);
    }

    /**
     * Store a newly created group in storage.
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
     * Display the documents and folders that
     * are shared inside the specified group.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $documents = $group->documents()->where('owner_id', '<>', Auth::id())->get();
        $folders = $group->folders()->where('owner_id', '<>', Auth::id())->get();
        $groups = Auth::user()->groups();

        return view('groups.sharing')->with([
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
     * Remove the specified group from storage.
     *
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
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

    /**
     * Add a member to the specified group.
     *
     * @param Request $request
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Edit the members for the specified group.
     *
     * @param Group $group
     * @return $this
     */
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
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
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
