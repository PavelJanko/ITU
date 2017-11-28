<ul class="nav nav-pills flex-column">
    <li class="nav-item">
        <a class="nav-link{{ isActiveRoute('groups.index') }}" href="{{ route('groups.index') }}">Vše</a>
    </li>
    @foreach(Auth::user()->groups as $group)
        <li class="nav-item">
            <a class="nav-link{{ isActiveRoute('groups.show', 'group', $group) }}" href="{{ route('groups.show', $group) }}">{{ $group->name }} <span class="badge badge-light">{{ $group->documents()->count() + $group->folders()->count() }}</span></a>
        </li>
    @endforeach
</ul>