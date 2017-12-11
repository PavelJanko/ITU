@extends('layouts.basic')

@section('content')
    <div class="container pt-3">
        <div class="row">
            <div class="col-8"><h3 class="mb-3">{{ $document->name }} (.{{ $document->extension }})</h3></div>
            <div class="col-4">
                <div class="btn-group pull-right" role="group">
                    <a href="{{ route('documents.download', $document->slug) }}" class="btn btn-secondary"><i class="fal fa-download"></i> Stáhnout</a>
                    @if($document->owner_id == Auth::id())
                        <a href="{{ route('documents.edit', $document->slug) }}" class="btn btn-warning"><i class="fal fa-edit"></i> Upravit</a>
                        <form action="{{ route('documents.destroy', $document->slug) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button class="btn btn-danger dialog-delete" type="submit"><i class="fal fa-file-times"></i> Odstranit</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <h5 class="mb-3">Klíčová slova:</h5>
        <p>
            @foreach($document->keywords as $keyword)
                @unless($loop->last)
                    {{ $keyword->name }},
                @else
                    {{ $keyword->name }}
                @endunless
            @endforeach
        </p>
        <h5 class="mb-3">Abstrakt:</h5>
        <p>{{ $document->abstract }}</p>
        <h5 class="mb-3">Komentáře:</h5>
        <div class="card{{ $document->comments->count() ? ' mb-3' : '' }}">
            <div class="card-header">
                Přidejte nový komentář
            </div>
            <div class="card-body">
                <form action="{{ route('comments.store') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="document_id" value="{{ $document->id }}">
                    <div class="form-group">
                        <label class="d-none" for="body">Example textarea</label>
                        <textarea class="form-control" name="body" id="body" placeholder="Nový komentář" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Odeslat</button>
                </form>
            </div>
        </div>
        @foreach($document->comments as $comment)
            <div class="card {{ $loop->last ? '' : ' mb-3' }}">
                <div class="card-header">
                    @if($document->owner_id == Auth::id() || $comment->author_id == Auth::id())
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                {{ $comment->author->name }} napsal {{ $comment->created_at->diffForHumans() }}
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <a class="dialog-delete btn btn-danger" href="#"><i class="fal fa-file-times"></i> Odstranit</a>
                                </form>
                            </div>
                        </div>
                    @else
                        {{ $comment->author->name }} napsal {{ $document->created_at->diffForHumans() }}
                    @endif
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $comment->body }}</p>
                </div>
            </div>
        @endforeach
    </div>
@endsection