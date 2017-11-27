@extends('layouts.basic')

@section('content')
    <div class="container">
        @if(session('statusType') && session('statusText'))
            @component('components.flash-message')
                @slot('type') {{ session('statusType') }} @endslot
                {!! session('statusText') !!}
            @endcomponent
        @endif
        <table class="dashboard-table table table-hover">
            <thead>
            <tr>
                <th scope="col">Název</th>
                <th scope="col">Přípona</th>
                <th scope="col">Vlastník</th>
            </tr>
            </thead>
            <tbody>
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
                            <td><a href="{{ route('documents.show', $document->slug) }}"><i class="fal fa-file-alt"></i> {{ $document->name }}</a></td>
                            <td>{{ $document->extension }}</td>
                            <td>{{ $document->owner->name }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            placement: 'left'
        });

        setTimeout(
            function() {
                $('.alert').fadeOut();
            }, 3000);
    </script>
@endsection