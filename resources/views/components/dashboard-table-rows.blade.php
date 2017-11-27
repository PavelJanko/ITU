@foreach($items as $item)
    <tr>
        @if($item instanceof \App\Document)
            <td><i class="fal fa-file-alt"></i> <a class="ml-1" data-toggle="tooltip" href="{{ route('documents.show', $item->slug) }}" title="<strong>Abstrakt:</strong><br>{{ str_limit($item->abstract, 200) }}">{{ $item->name }}</a></td>
        @else
            <td><i class="fal fa-folder"></i> <a class="ml-1" href="{{ route('folders.show', $item->slug) }}">{{ $item->name }}</a></td>
        @endif
        <td>{{ $item->extension == NULL ? 'Složka' : $item->extension }}</td>
        @if($item->owner->id != Auth::id())
            <td>{{ $item->owner->name }}</td>
        @else
            <td>{{ $item->created_at->diffForHumans() }}</td>
            <td>
                <div class="btn-group" role="group">
                    @if($item instanceof \App\Folder)
                        <a class="btn btn-secondary" href="{{ route('folder.sharingEdit', $item->slug) }}" role="button"><i class="fal fa-share-alt"></i> Sdílení</a>
                    @else
                        <a class="btn btn-secondary" href="{{ route('document.sharingEdit', $item->slug) }}" role="button"><i class="fal fa-share-alt"></i> Sdílení</a>
                    @endif
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">Další</button>
                        <div class="dropdown-menu">
                            @if($item instanceof \App\Folder)
                                <form action="{{ route('folders.destroy', $item->slug) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="dropdown-item"><i class="fal fa-times-square"></i> Odstranit</button>
                                </form>
                                <a class="dropdown-item folder-rename" href="{{ route('folders.update', $item->slug) }}"><i class="fal fa-edit"></i> Přejmenovat</a>
                            @else
                                <form action="{{ route('documents.destroy', $item->slug) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="dropdown-item"><i class="fal fa-file-times"></i> Odstranit</button>
                                </form>
                                <a class="dropdown-item" href="{{ route('documents.download', $item->slug) }}"><i class="fal fa-download"></i> Stáhnout</a>
                            @endif
                        </div>
                    </div>
                </div>
            </td>
        @endif
    </tr>
@endforeach