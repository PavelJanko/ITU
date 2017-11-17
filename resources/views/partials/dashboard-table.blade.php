<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Název</th>
        <th scope="col">Přípona</th>
        <th scope="col">Čas přidání</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($parentFolder) && $parentFolder != NULL)
            <tr>
                @if($parentFolder->parent != NULL)
                    <td class="testFolder"><a href="{{ route('dashboard.folder', $parentFolder->parent->slug) }}">..</a></td>
                @else
                    <td class="testFolder"><a href="{{ route('dashboard.folder') }}">..</a></td>
                @endif
                <td></td>
                <td></td>
            </tr>
        @endif
        @foreach($folders as $folder)
            <tr>
                <td class="testFolder"><a href="{{ route('dashboard.folder', $folder->slug) }}">{{ $folder->name }}</a></td>
                <td>Složka</td>
                <td>{{ $folder->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        @foreach($documents as $document)
            <tr>
                <td>{{ $document->name }}</td>
                <td>{{ $document->suffix }}</td>
                <td>{{ $document->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>