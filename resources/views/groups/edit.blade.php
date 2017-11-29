@extends('layouts.basic')

@section('content')
    <div class="container">
        <a class="btn btn-secondary" href="{{ route('groups.edit') }}" role="button"><i class="fal fa-list"></i> Vytvořit novou skupinu</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Název</th>
                <th scope="col">Zakladatel</th>
                <th scope="col">Akce</th>
            </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->creator->id == Auth::id() ? 'Vy' : $group->creator->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalDocumentNew"><i class="fal fa-file-plus"></i> Členové</button>
                                @if($group->creator->id == Auth::id())
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-secondary rounded-0"><i class="fal fa-times-square"></i> Odstranit</button>
                                        </form>
                                    </div>
                                @endif
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalFolderNew"><i class="fal fa-plus"></i> Opustit</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection