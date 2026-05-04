@extends('layouts.unsubscribe')

@section('template_title')
    Unsubscribe
@endsection

@section('content')

    <form action="javascript:void(0)" id="unSub">
        <input type="hidden" name="type" value="{{ \Request::segment(2) }}">
        <input type="hidden" name="id" value="{{ \Request::segment(3) }}">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.11/dist/sweetalert2.all.min.js"></script>

    <script>
        window.onload = function() {
            swal({
                title: 'Are you sure?',
                text: "You won't be unsubscribe emails!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    swal(
                        'Unsubscribe!',
                        'Your have Unsubscribe Emails.',
                        'success'
                    );

                    var data = $("#unSub").serialize();

                    $.ajax({
                        type: "GET",
                        url: "/save/unsub",
                        dataType: "json",
                        data: data,
                        success: function (response) {
                            console.log(response)
                            setTimeout(function () {
                                window.close();
                            },2000)
                        }
                    });
                } else {
                    window.close();
                }
            })
        }
    </script>

@endsection
