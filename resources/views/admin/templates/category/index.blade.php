@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex">
                    <h5 class="title">Category List</h5>
                    <a class="btn btn-info btn-sm ml-auto"
                    href="{{ route('category.create') }}">Add</a>
                </div>
                <div class="card-body">
                    <table id="category-datatable" class="table table-striped dt-responsive nowrap">
                        <thead>
                            <tr>
                                <!--<th>#ID</th>-->
                                <th>Name</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('file_script')
<script>
    var a = $('#category-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("category.index")}}',
        columns: [
        {data: 'name', name: 'name'},
        {data: 'img_uri', name: 'image', orderable: false, searchable: false},
        {data: 'status', name: 'status'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    $('#category-datatable tbody').on('click', '.deletebutton', function () {
        var uid = $(this).attr("data-id");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            cancelButtonClass: "btn-success",
            showCancelButton: true,
            closeOnConfirm: false,
            closeOnCancel: false,
            reverseButtons: true
        }).then((isConfirm) => {
            var _token = '{{ csrf_token() }}';
            var _url = '{{ url("category") }}/'+uid;
            if (isConfirm) {
                $.ajax({
                    type: "DELETE",
                    url: _url,
                    data: {
                        id: uid,
                        _token: _token
                    },
                    success: function (data) {
                        swal("Deleted!", "Category has been deleted.", "success");
                        $("#category-datatable").DataTable().ajax.reload();
                    }, error: function () {
                        swal("Deleted!", "Something Went wrong deleted failed.", "error");
                    }
                });
            } else {
                swal("Cancelled", "Data is safe :)", "error");
            }
        });
    });
</script>
@endsection