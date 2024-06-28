@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Course Material Upload</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Course Material</a></li>
                        <li class="breadcrumb-item active">Upload</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Upload file</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <form class="row align-items-center" method="post" action="{{ url('multiple-file-upload') }}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="col">
                                            <input type="file" name="fileuploads[]" class="form-control" multiple>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <button type="submit" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Upload Files</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>Filename</th>
                                        <th>Filepath</th>
                                        <th>File Type</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fileUploads as $fileUpload)
                                        <tr>
                                            <td>{{ $fileUpload->filename }}</td>
                                            <td>{{ $fileUpload->filepath }}</td>
                                            <td>{{ $fileUpload->type }}</td>
                                            <td class="text-end">
                                                <button class="btn btn-danger btn-sm delete-file-btn" 
                                                        data-file-id="{{ $fileUpload->id }}" 
                                                        data-filename="{{ $fileUpload->filename }}">
                                                        <i class="far fa-trash-alt me-2"></i>Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', function () {
    @if ($message = Session::get('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ $message }}'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>'
        });
    @endif

    document.querySelectorAll('.delete-file-btn').forEach(button => {
        button.addEventListener('click', function () {
            var fileId = this.getAttribute('data-file-id');
            var filename = this.getAttribute('data-filename');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('file.delete') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            file_id: fileId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the file.',
                                'error'
                            );
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'Your file is safe :)',
                        'info'
                    );
                }
            });
        });
    });
});

</script>

@endsection
