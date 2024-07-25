@extends('layout')
@section('content')
    <a class="btn btn-primary ml-auto" href="{{ route('course.create') }}">Add Course</a>
    <div class="row m-3">
        <div class="col-md-6">
            <input type="text" name="search" placeholder="Search" class="form-control" id="all_search">
        </div>

        {!! $html->table(
            ['class' => 'courses table text-center table-sortable responsive table', 'width' => '100%'],
            true,
        ) !!}
    @endsection
    @section('script')
        {!! $html->scripts() !!}
        <script>
            $(document).on('click', '.delete', function() {
                var url = $(this).data('url');
                var userConfirmed = confirm("Do you want to proceed?");
                if (userConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            _method: "DELETE"
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            $('.courses').DataTable().ajax.reload(null,
                                false);
                        }
                    });
                }
            });
            $("#all_search").on("keyup", function() {
                $(".courses").DataTable().ajax.reload();
            });
        </script>
    @endsection
