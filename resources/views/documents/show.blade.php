@extends('layouts.basic')

@section('content')
    <div class="container pt-3">
        <div class="row">
            <div class="col-9"><h3 class="mb-3">{{ $document->name }} (.{{ $document->extension }})</h3></div>
            <div class="col-3">
                <a href="{{ route('documents.download', $document->slug) }}" class="btn btn-secondary pull-right">Stáhnout</a>
            </div>
        </div>
        <h5 class="mb-3">Abstrakt:</h5>
        <p>{{ $document->abstract }}</p>
        <h5 class="mb-3">Komentáře:</h5>
        <div class="card mb-3">
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
                    {{ $comment->author->name }} <span class="pull-right">napsal {{ $document->created_at->diffForHumans() }}</span>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $comment->body }}</p>
                </div>
            </div>
        @endforeach
    </div>
@endsection