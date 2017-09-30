@extends('core')

@section('content')
    <div class="container">
        <p class="my-3">Vyhledávání podle klíčových slov:
            @foreach($keywords as $keyword)
                <a href="#">{{ $keyword->name }}</a>
            @endforeach
        </p>
        <div class="card-columns">
            @foreach($documents as $document)
                <div class="card">
                    <img src="{{ $document->getMedia()->first()->getUrl('thumb') }}" alt="{{ $document->name }} thumbnail" class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title"><a href="{{ route('document.show', ['slug' => $document->slug]) }}">{{ $document->name }}</a></h4>
                        <p class="card-text">
                            @foreach($document->keywords as $keyword)
                                <a href="#">{{ $keyword->name }}</a>
                            @endforeach
                        </p>
                        <p class="card-text">{{ str_limit($document->abstract, 200) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
        {{ $documents->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection