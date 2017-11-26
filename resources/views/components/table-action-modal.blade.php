<div class="modal fade" id="{{ $slot }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            @if($type == 'document')
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @elseif($type == 'folderNew')
                <form action="{{ route('folders.store') }}" method="POST" enctype="multipart/form-data">
            @else
                <form action="#" method="POST" enctype="multipart/form-data">
            @endif
                <div class="modal-header">
                    @if($type == 'documentNew')
                        <h5 class="modal-title">Nahrání nového dokumentu</h5>
                    @elseif($type == 'folderNew')
                        <h5 class="modal-title">Vytvoření nové složky</h5>
                    @else
                        <h5 class="modal-title">Upravení názvu složky</h5>
                    @endif
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    @if($type == 'folderUpdate')
                        {{ method_field('PUT') }}
                    @endif
                    @if(isset($parentFolderId))
                        <input type="hidden" name="parent_id" value="{{ $parentFolderId }}">
                    @endif
                    @if($type == 'document')
                        <div class="form-row align-items-center">
                            <div class="col-3">
                                <label class="mb-0" for="modalDocumentNewAbstract">Abstrakt:</label>
                            </div>
                            <div class="col-9">
                                <textarea class="form-control" id="modalDocumentNewAbstract" name="abstract" rows="6"></textarea>
                            </div>
                        </div>
                        <div class="form-row align-items-center justify-content-end mt-2">
                            <div class="col-9">
                                <input type="file" class="form-control-file" id="modalDocumentNewFile" name="document">
                            </div>
                        </div>
                    @else
                        <div class="form-row align-items-center">
                            <div class="col-3">
                                <label class="mb-0" for="modal{{ ucfirst($type) }}Name">Název:</label>
                            </div>
                            <div class="col-9">
                                <input type="text" class="form-control" id="modal{{ ucfirst($type) }}Name" name="name" placeholder="Nová složka">
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    @if($type == 'document')
                        <button type="submit" class="btn btn-primary"><i class="fal fa-file-plus"></i> Nahrát</button>
                    @elseif($type == 'folderNew')
                        <button type="submit" class="btn btn-primary"><i class="fal fa-plus"></i> Vytvořit</button>
                    @else
                        <button type="submit" class="btn btn-primary"><i class="fal fa-edit"></i> Přejmenovat</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>