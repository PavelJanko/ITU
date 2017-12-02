@extends('layouts.form')

@section('content')
    @component('components.table-action-modal', ['placeholder' => 'novy@clen.com', 'route' => route('groups.addMember', $group->id), 'type' => 'memberNew']) modalMemberNew @endcomponent
    <form action="{{ route('groups.update', $group->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <h5>{{ $group->name }}</h5>
        @foreach($group->members as $member)
            @if($group->creator_id != $member->id)
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="members[]" value="{{ $member->id }}" checked>
                        {{ $member->name }}
                    </label>
                </div>
            @endif
        @endforeach
        <small class="form-text text-muted mt-0 mb-2">Odškrtněte uživatele, které chcete odebrat ze skupiny</small>
        <button type="submit" class="btn btn-primary">Upravit</button>
        <button class="btn btn-link member-add">Přidat člena</button>
    </form>
@endsection

@section('scripts')
    @parent

    <script>
        triggerModalFocus('#modalMemberNew', '#modalMemberNewEmail');

        $('.member-add').click(function(e) {
           e.preventDefault();

           $('#modalMemberNew').modal();
        });
    </script>
@endsection