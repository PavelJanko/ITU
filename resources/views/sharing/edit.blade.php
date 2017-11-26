@extends('layouts.basic')

@section('content')
    <div class="container">
        <form action="{{  route($type . '.sharingUpdate', $item->slug) }}" method="POST">
            {{ csrf_field() }}
            @foreach($ownerGroups as $group)
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="groups[]" value="{{ $group->id }}"{{ $itemGroupIds->contains($group->id) ? ' checked' : '' }}>
                        {{ $group->name }}
                    </label>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Sdílet</button>
        </form>
    </div>
@endsection