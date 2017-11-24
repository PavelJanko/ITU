@component('components.table-action-modal', ['type' => 'document']) newDocument @endcomponent
@component('components.table-action-modal', ['type' => 'folderNew']) newFolder @endcomponent
@component('components.table-action-modal', ['type' => 'folderUpdate', 'route' => '#']) updateFolder @endcomponent
@if(session('statusType') && session('statusText'))
    @component('components.flash-message')
        @slot('type') {{ session('statusType') }} @endslot
        {!! session('statusText') !!}
    @endcomponent
@endif
<div class="btn-group" role="group">
    <button id="newDocumentButton" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#newDocument"><i class="fal fa-file-plus"></i> Nahrát</button>
    <button id="newFolderButton" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#newFolder"><i class="fal fa-plus"></i> Nová složka</button>
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
        setTimeout(
            function() {
                $('.alert').fadeOut();
            }, 3000);

        $('.folder-rename').click(function(e) {
            e.preventDefault();
            $('#folderUpdate').attr('action', $(e.target).attr('href'));
        });
    </script>
@endsection