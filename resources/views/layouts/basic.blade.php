@extends('core')

@section('layout')
    @yield('content')
@endsection

@section('scripts')
    @parent

    <script>
        $('.dialog-delete, .dialog-leave').click(function(e) {
            e.preventDefault();

            let alertText = 'Jste si jisti, že chcete položku odstranit?';

            if(~($(e.target).attr('class')).indexOf('dialog-leave'))
                alertText = 'Jste si jisti, že chcete skupinu opustit?';

            swal({
                title: 'Pozor!',
                text: alertText,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ano',
                cancelButtonText: 'Ne'
            }).then((result) => {
                if(result.value) {
                    if(~($(e.target).attr('class')).indexOf('dialog-leave')) {
                        $(location).attr('href', $(e.target).attr('href'));
                    } else
                        $(e.target).parent().submit();
                }
            });
        });
    </script>
@endsection