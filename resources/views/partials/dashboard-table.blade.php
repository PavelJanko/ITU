@component('components.table-action-modal', ['type' => 'document']) newDocument @endcomponent
@component('components.table-action-modal', ['type' => 'folderNew']) newFolder @endcomponent
@component('components.table-action-modal', ['type' => 'folderUpdate', 'route' => '#']) updateFolder @endcomponent
<div class="btn-group" role="group">
    <button id="newDocumentButton" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#newDocument">Nahrát</button>
    <button id="newFolderButton" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#newFolder">Nová složka</button>
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