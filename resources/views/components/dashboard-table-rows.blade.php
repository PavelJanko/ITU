@foreach($items as $item)
    <tr>
        @if($item instanceof \App\Document)
            <td><a href="{{ route('document.download', $item->slug) }}">{{ $item->name }}</a></td>
        @else
            <td><a href="{{ route('folder.show', $item->slug) }}">{{ $item->name }}</a></td>
        @endif
        <td>{{ $item->extension == NULL ? 'Složka' : $item->extension }}</td>
        <td>{{ $item->created_at->diffForHumans() }}</td>
        <td>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-secondary" data-toggle="modal">Sdílet</button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">Další</button>
                    <div class="dropdown-menu">
                        @if($item instanceof \App\Folder)
                            <form action="{{ route('folder.destroy', $item->slug) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="dropdown-item">Odstranit</button>
                            </form>
                            <a class="dropdown-item folder-rename" data-toggle="modal" data-target="#updateFolder" href="{{ route('folder.update', $item->slug) }}">Přejmenovat</a>
                        @else
                            <form action="{{ route('document.destroy', $item->slug) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="dropdown-item">Odstranit</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </td>
    </tr>
@endforeach

@section('scripts')
    <script>
        $('.folder-rename').click(function(e) {
            e.preventDefault();
            $('#folderUpdate').attr('action', $(e.target).attr('href'));
        });
    </script>
@endsection