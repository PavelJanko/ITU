@extends('layouts.basic')

@section('content')
    @component('components.table-action-modal', ['placeholder' => 'Nová skupina', 'route' => route('groups.store'), 'type' => 'groupNew']) modalGroupNew @endcomponent
    <div class="container">
        <button type="button" class="btn btn-success my-2" data-toggle="modal" data-target="#modalGroupNew"><i class="fal fa-plus"></i> Nová skupina</button>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Název</th>
                    <th scope="col">Zakladatel</th>
                    <th scope="col">Akce</th>
                </tr>
            </thead>
            <tbody>
                @unless($groups->count())
                    <td colspan="3">Nebyly nalezeny žádné skupiny.</td>
                @endif
                @foreach($groups as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->creator->id == Auth::id() ? 'Vy' : $group->creator->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                @if($group->creator->id == Auth::id())
                                    <a class="btn btn-secondary" href="{{ route('groups.editMembers', $group->id) }}"><i class="fal fa-user-circle"></i> Členové</a>
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <a class="dialog-delete btn btn-secondary rounded-0" href="#"><i class="fal fa-times-square"></i> Odstranit</a>
                                        </form>
                                    </div>
                                @endif
                                <a class="btn btn-secondary dialog-leave" href="{{ route('groups.leave', $group->id) }}"><i class="fal fa-eject"></i> Opustit</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    @parent

    <script>
        triggerModalFocus('#modalGroupNew', '#modalGroupNewName');
    </script>
@endsection