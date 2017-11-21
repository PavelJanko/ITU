@extends('layouts.basic')

@section('content')
    <div class="container">
        @include('partials.dashboard-table')
    </div>
@endsection

@section('scripts')
    <script>
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            placement: 'left'
        });

        $('#newDocument').on('shown.bs.modal', function() {
            $('#modalDocumentAbstract').trigger('focus')
        });

        $('#newFolder').on('shown.bs.modal', function() {
            $('#modalFolderName').trigger('focus')
        });
    </script>
@endsection