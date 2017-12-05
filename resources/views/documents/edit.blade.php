@extends('layouts.basic')

@section('content')
    <div class="container pt-3">
        <h3 class="mb-3">{{ $document->name }} (.{{ $document->extension }})</h3>
        <form action="{{ route('documents.update', $document->slug) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <h5 class="mb-3">Klíčová slova:</h5>
            <label class="d-none" for="select2KeywordsEdit"></label>
            <select id="select2KeywordsEdit" class="form-control" name="keywords[]" multiple="multiple" style="width: 30rem;">
                @foreach($document->keywords as $keyword)
                    <option value="{{ $keyword->name }}" selected="selected">{{ $keyword->name }}</option>
                @endforeach
            </select>
            <h5 class="my-3">Abstrakt:</h5>
            <label class="d-none" for="abstract"></label>
            <textarea class="form-control mb-3" id="abstract" name="abstract" rows="12">{{ $document->abstract }}</textarea>
            <button type="submit" class="btn btn-success">Upravit</button>
        </form>
    </div>
@endsection

@section('scripts')
    @parent

    <script>
        $('#select2KeywordsEdit').select2({
            language: 'cs',
            maximumSelectionLength: 3,
            minimumInputLength: 1,
            placeholder: 'Zadejte klíčová slova',
            tags: true,
            theme: 'bootstrap',
            width: 'resolve'
        });
    </script>
@endsection