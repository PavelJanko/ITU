@extends('layouts.form')

@section('content')
    <form action="{{ route($type . '.sharingUpdate', $item->slug) }}" method="POST">
        {{ csrf_field() }}
        <h5>{{ $item->name }}</h5>
        @foreach($ownerGroups as $ownerGroup)
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="groups[]" value="{{ $ownerGroup->id }}"{{ $itemGroups->pluck('id')->contains($ownerGroup->id) ? ' checked' : '' }}>
                    {{ $ownerGroup->name }}
                </label>
            </div>
        @endforeach
        <small class="form-text text-muted mt-0 mb-2">Zaškrtněte skupiny, se kterými chcete položku sdílet</small>
        <button type="submit" class="btn btn-primary">Upravit sdílení</button>
        <a class="btn btn-link" href="{{ route('groups.edit') }}">Vytvořit novou skupinu</a>
    </form>
@endsection
