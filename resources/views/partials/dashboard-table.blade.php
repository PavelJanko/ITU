@if(isset($parentFolder) && $parentFolder->owner->id != Auth::id())
    <div class="row">
        <div class="col-3">
            @include('partials.sharing-nav')
        </div>
        <div class="col-9">
            <a class="btn btn-secondary" href="{{ route('groups.edit') }}" role="button"><i class="fal fa-list"></i> Spravovat skupiny</a>
@else
    @component('components.table-action-modal', ['route' => route('documents.store'), 'type' => 'document']) modalDocumentNew @endcomponent
    @component('components.table-action-modal', ['placeholder' => 'Nová složka', 'route' => route('folders.store'), 'type' => 'folderNew']) modalFolderNew @endcomponent
    @component('components.table-action-modal', ['placeholder' => 'Nová složka', 'type' => 'folderUpdate']) modalFolderUpdate @endcomponent
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalDocumentNew"><i class="fal fa-file-plus"></i> Nahrát</button>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalFolderNew"><i class="fal fa-plus"></i> Nová složka</button>
    </div>
@endif
            <table class="dashboard-table table table-hover">
                <thead>
                <tr>
                    <th scope="col">Název</th>
                    <th scope="col">Přípona</th>
                    @if(isset($parentFolder) && $parentFolder->owner->id != Auth::id())
                        <th scope="col">Vlastník</th>
                    @else
                        <th scope="col">Čas přidání</th>
                        <th scope="col">Akce</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    @component('components.dashboard-table-rows', ['items' => $folders]) @endcomponent
                    @component('components.dashboard-table-rows', ['items' => $documents]) @endcomponent
                </tbody>
            </table>
@if(isset($parentFolder) && $parentFolder->owner->id != Auth::id())
        </div>
    </div>
@endif

@section('scripts')
    @parent
    <script>
        $(window).on('load', function() {
            const modalFolderUpdateId = $('#modalFolderUpdate');

            $('.folder-rename').click(function(e) {
                e.preventDefault();

                modalFolderUpdateId.modal();

                if($(e.target).is('svg'))
                    modalFolderUpdateId.find('form').attr('action', $(e.target).parent().attr('href'));
                else
                    modalFolderUpdateId.find('form').attr('action', $(e.target).attr('href'));
            });

            $('[data-toggle="tooltip"]').tooltip({
                html: true,
                placement: 'left'
            });
            
            triggerModalFocus('#modalDocumentNew', '#modalDocumentNewAbstract');
            triggerModalFocus('#modalFolderNew', '#modalFolderNewName');
            triggerModalFocus(modalFolderUpdateId, '#modalFolderUpdateName');
        });
    </script>
@endsection