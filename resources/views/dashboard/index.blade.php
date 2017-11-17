@extends('core')

@section('content')
    <div class="container" id="dashboardTable">
        @include('partials.dashboard-table')
    </div>
@endsection

@section('scripts')
    <script>
        $(document.body).on('click', '.testFolder', function(e) {
            e.preventDefault();
            axios.get($(event.target).attr('href'))
                .then(response => $('#dashboardTable').html(response.data));
        });
    </script>
@endsection