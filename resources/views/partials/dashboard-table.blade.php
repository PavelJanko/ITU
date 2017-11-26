@component('components.table-action-modal', ['type' => 'document']) modalDocumentNew @endcomponent
@component('components.table-action-modal', ['type' => 'folderNew']) modalFolderNew @endcomponent
@component('components.table-action-modal', ['type' => 'folderUpdate']) modalFolderUpdate @endcomponent
@if(session('statusType') && session('statusText'))
    @component('components.flash-message')
        @slot('type') {{ session('statusType') }} @endslot
        {!! session('statusText') !!}
    @endcomponent
@endif
<div class="btn-group" role="group">
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalDocumentNew"><i class="fal fa-file-plus"></i> Nahrát</button>
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalFolderNew"><i class="fal fa-plus"></i> Nová složka</button>
</div>
<table class="dashboard-table table table-hover">
    <thead>
    <tr>
        <th scope="col">Název</th>
        <th scope="col">Přípona</th>
        <th scope="col">Čas přidání</th>
        <th scope="col">Akce</th>
    </tr>
    </thead>
    <tbody>
        @component('components.dashboard-table-rows', ['items' => $folders]) @endcomponent
        @component('components.dashboard-table-rows', ['items' => $documents]) @endcomponent
    </tbody>
</table>

@section('scripts')
    <script>
        $(window).on('load', function() {
            setTimeout(
                function() {
                    $('.alert').fadeOut();
                }, 3000);

            const modalFolderUpdate = $('#modalFolderUpdate');

            $('.folder-rename').click(function(e) {
                e.preventDefault();
                modalFolderUpdate.modal();
                if($(e.target).is('svg'))
                    modalFolderUpdate.find('form').attr('action', $(e.target).parent().attr('href'));
                else
                    modalFolderUpdate.find('form').attr('action', $(e.target).attr('href'));
            });

            $('[data-toggle="tooltip"]').tooltip({
                html: true,
                placement: 'left'
            });

            $('#modalDocumentNew').on('shown.bs.modal', function() {
                $('#modalDocumentNewAbstract').trigger('focus');
            });

            $('#modalFolderNew').on('shown.bs.modal', function() {
                $('#modalFolderNewName').trigger('focus');
            });

            $('#modalFolderUpdate').on('shown.bs.modal', function() {
                $('#modalFolderUpdateName').trigger('focus');
            });
        });
    </script>
@endsection