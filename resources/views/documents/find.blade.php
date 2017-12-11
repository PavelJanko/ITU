@extends('layouts.basic')

@section('content')
    <div class="container">
        @if(isset($parentFolder) && $parentFolder->owner->id != Auth::id())
            <div class="row">
                <div class="col-3">
                    @include('partials.sharing-nav')
                </div>
                <div class="col-9">
                    <a class="btn btn-secondary" href="{{ route('groups.edit') }}" role="button"><i class="fal fa-list"></i> Spravovat skupiny</a>
        @else
            @component('components.table-action-modal', ['parentFolder' => isset($parentFolder) ? $parentFolder : NULL, 'route' => route('documents.store'), 'type' => 'document']) modalDocumentNew @endcomponent
            @component('components.table-action-modal', ['parentFolder' => isset($parentFolder) ? $parentFolder : NULL, 'placeholder' => 'Nová složka', 'route' => route('folders.store'), 'type' => 'folderNew']) modalFolderNew @endcomponent
            @component('components.table-action-modal', ['parentFolder' => isset($parentFolder) ? $parentFolder : NULL, 'placeholder' => 'Nová složka', 'type' => 'folderUpdate']) modalFolderUpdate @endcomponent
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
                            @if(isset($folders) && $folders == NULL && $documents == NULL)
                                <tr>
                                    <td colspan="{{ isset($parentFolder) && $parentFolder->owner->id != Auth::id() ? '3' : '4' }}">Nebyly nalezeny žádné položky.</td>
                                </tr>
                            @elseif(isset($folders))
                                @component('components.dashboard-table-rows', ['items' => $folders]) @endcomponent
                            @endif
                            @component('components.dashboard-table-rows', ['items' => $documents]) @endcomponent
                        </tbody>
                    </table>
        @if(isset($parentFolder) && $parentFolder->owner->id != Auth::id())
                </div>
            </div>
        @endif
    </div>
@endsection

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

            $('#modalDocumentNew').on('shown.bs.modal', function() {
                $('#select2KeywordsModal').select2({
                    ajax: {
                        url: 'http://itu.app/keywords',
                        dataType: 'json',
                        data: function(params) {
                            return {
                                term: $.trim(params.term)
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                    },
                    language: 'cs',
                    maximumSelectionLength: 3,
                    minimumInputLength: 1,
                    placeholder: 'Zadejte klíčová slova',
                    tags: true,
                    theme: 'bootstrap',
                    width: 'resolve'
                });
            });

            triggerModalFocus('#modalDocumentNew', '#modalDocumentNewAbstract');
            triggerModalFocus('#modalFolderNew', '#modalFolderNewName');
            triggerModalFocus(modalFolderUpdateId, '#modalFolderUpdateName');
        });
    </script>
@endsection