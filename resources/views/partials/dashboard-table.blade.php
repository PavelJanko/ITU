<div class="modal fade" id="newDocument" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Nahrání nového dokumentu</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    @if(isset($parentFolder))
                        <input type="hidden" name="parent_id" value="{{ $parentFolder->id }}">
                    @endif
                    <div class="form-row align-items-center">
                        <div class="col-3">
                            <label class="mb-0" for="modalDocumentAbstract">Abstrakt:</label>
                        </div>
                        <div class="col-9">
                            <textarea class="form-control" id="modalDocumentAbstract" name="abstract" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="form-row align-items-center justify-content-end mt-2">
                        <div class="col-9">
                            <input type="file" class="form-control-file" id="modalDocument" name="document">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Vytvořit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="newFolder" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('folder.store') }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Vytvoření nové složky</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    @if(isset($parentFolder))
                        <input type="hidden" name="parent_id" value="{{ $parentFolder->id }}">
                    @endif
                    <div class="form-row align-items-center">
                        <div class="col-3">
                            <label class="mb-0" for="modalFolderName">Název složky:</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control" id="modalFolderName" name="name" placeholder="Nová složka">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Vytvořit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="btn-group" role="group">
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#newDocument">Nahrát</button>
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#newFolder">Nová složka</button>

    <div class="btn-group" role="group">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">Další</button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Dropdown link</a>
            <a class="dropdown-item" href="#">Dropdown link</a>
        </div>
    </div>
</div>
<table class="table table-hover">
    <thead>
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
                    <td class="testFolder"><a href="{{ route('folder.contents', $parentFolder->parent->slug) }}">..</a></td>
                @else
                    <td class="testFolder"><a href="{{ route('folder.contents') }}">..</a></td>
                @endif
                <td></td>
                <td></td>
            </tr>
        @endif
        @foreach($folders as $folder)
            <tr>
                <td class="testFolder"><a href="{{ route('folder.contents', $folder->slug) }}">{{ $folder->name }}</a></td>
                <td>Složka</td>
                <td>{{ $folder->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        @foreach($documents as $document)
            <tr>
                <td><a href="{{ route('document.download', $document->slug) }}">{{ $document->name }}</a></td>
                <td>{{ $document->extension }}</td>
                <td>{{ $document->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>