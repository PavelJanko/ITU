@extends('layouts.basic')

@section('content')
    <div class="container">
        @if(session('statusType') && session('statusText'))
            @component('components.flash-message')
                @slot('type') {{ session('statusType') }} @endslot
                {!! session('statusText') !!}
            @endcomponent
        @endif
        <div class="row mt-2">
            <div class="col-3">
                @include('partials.sharing-nav')
            </div>
            <div class="col-9">
                <a class="btn btn-info mb-2" href="{{ route('groups.edit') }}" role="button"><i class="fal fa-list"></i> Spravovat skupiny</a>
                <table class="dashboard-table table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Název</th>
                        <th scope="col">Přípona</th>
                        <th scope="col">Vlastník</th>
                    </tr>
                    </thead>
                    <tbody>
                    @unless($folders->count() || $documents->count())
                        <tr>
                            <td colspan="3">Nebyly nalezeny žádné položky</td>
                        </tr>
                    @else
                        @foreach($folders as $folder)
                            @if(!$folders->pluck('id')->contains($folder->parent_id))
                                <tr>
                                    <td><i class="fal fa-folder"></i> <a href="{{ route('folders.show', $folder->slug) }}">{{ $folder->name }}</a></td>
                                    <td>Složka</td>
                                    <td>{{ $folder->owner->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                        @foreach($documents as $document)
                            @if(!$folders->pluck('id')->contains($document->parent_id))
                                <tr>
                                    <td><i class="fal fa-file-alt"></i> <a data-toggle="tooltip" href="{{ route('documents.show', $document->slug) }}" title="<strong>Abstrakt:</strong><br>{{ str_limit($document->abstract, 200) }}">{{ $document->name }}</a></td>
                                    <td>{{ $document->extension }}</td>
                                    <td>{{ $document->owner->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            placement: 'left'
        });
    </script>
@endsection