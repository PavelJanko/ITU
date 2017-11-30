@if(isset($parentFolder) && $parentFolder->owner->id != Auth::id())
    <div class="row">
        <div class="col-3">
            @include('partials.sharing-nav')
        </div>
        <div class="col-9">
            <a class="btn btn-secondary" href="{{ route('groups.edit') }}" role="button"><i class="fal fa-list"></i> Spravovat skupiny</a>
@else
    @component('components.table-action-modal', ['type' => 'document']) modalDocumentNew @endcomponent
    @component('components.table-action-modal', ['type' => 'folderNew']) modalFolderNew @endcomponent
    @component('components.table-action-modal', ['type' => 'folderUpdate']) modalFolderUpdate @endcomponent
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
            @if(session('statusType') && session('statusTitle') && session('statusText'))
                swal('{{ session('statusTitle') }}', '{{ session('statusText') }}', '{{ session('statusType') }}');
            @endif

            const modalFolderUpdateId = $('#modalFolderUpdateId');

            $('.document-delete, .folder-delete').click(function(e) {
                e.preventDefault();

                swal({
                    title: 'Pozor!',
                    text: "Jste si jisti, že chcete položku odstranit?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ano',
                    cancelButtonText: 'Ne'
                }).then((result) => {
                    if(result.value) {
                        $(e.target).parent().submit();
                    }
                });

                if($(e.target).is('svg'))
                    modalFolderUpdateId.find('form').attr('action', $(e.target).parent().attr('href'));
                else
                    modalFolderUpdateId.find('form').attr('action', $(e.target).attr('href'));
            });

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
            
            var triggerModalFocus = function(modalId, inputId) {
                $(modalId).on('shown.bs.modal', function() {
                    $(inputId).trigger('focus');
                });
            };
            
            triggerModalFocus('#modalDocumentNew', '#modalDocumentNewAbstract');
            triggerModalFocus('#modalFolderNew', '#modalFolderNewName');
            triggerModalFocus(modalFolderUpdateId, '#modalFolderUpdateIdName');
        });
    </script>
@endsection