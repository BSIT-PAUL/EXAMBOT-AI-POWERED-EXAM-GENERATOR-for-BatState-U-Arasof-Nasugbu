@extends('layouts.master')

@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Exam Management</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Exam</a></li>
                        <li class="breadcrumb-item active">Generate</li>
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
                                    <h3 class="page-title">Exam Type Generation</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <form action="{{ route('generate.exam.type.table') }}" method="POST" id="uploadForm">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger me-2">
                                            <i class="fas fa-book-reader"></i> Generate Exam Type
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            @if(!empty($examTypes) && count($examTypes) > 0)
                                @foreach($examTypes as $topic => $types)
                                    <div class="topic-section mb-4">
                                        <h3 class="topic-title">Main Topic: {{ $topic ?? '' }}</h3>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Bloom's Level</th>
                                                        <th>Items</th>
                                                        <th>Exam Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($types as $type)
                                                        <tr>
                                                            <td>{{ ucfirst($type['level']) ?? '' }}</td>
                                                            <td>{{ $type['items'] ?? ''  }}</td>
                                                            <td>{{ $type['exam_type'] ?? '' }}</td>
                                                            <td class="text-center">
                                                                <button class="btn btn-outline-danger btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-topic="{{ $topic }}" data-level="{{ $type['level'] }}" data-items="{{ $type['items'] }}" data-exam-type="{{ $type['exam_type'] }}">
                                                                    <i class="fas fa-edit me-2"></i>Edit Exam Type
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                                @if(isset($generatedAt))
                                    <p class="text-end text-muted">Last generated at: {{ $generatedAt ?? '' }}</p>
                                @endif
                            @else
                                <p>No exam types generated yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Exam Type -->
<div class="modal custom-modal fade" id="editModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0">Edit Exam Type</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ route('edit.exam.type') }}">
                    @csrf
                    <div class="bank-inner-details">
                        <div class="row mb-3">
                            <div class="col-lg-12 col-md-6 mb-3">
                                <label for="editTopic" class="form-label text">Topic</label>
                                <input type="text" class="form-control" name="topic" id="editTopic" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label for="editLevel" class="form-label">Bloom's Level</label>
                                <input type="text" class="form-control" name="level" id="editLevel" readonly>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label for="editItems" class="form-label">Number of Items</label>
                                <input type="number" class="form-control" name="items" id="editItems" readonly>
                            </div>
                            <div class="col-lg-4 col-md-8 mb-3">
                                <label for="editExamType" class="form-label">Exam Type</label>
                                <select class="form-control" name="exam_type" id="editExamType" required>
                                    <option value="Fill-in-the-blank">Fill-in-the-blank</option>
                                    <option value="Matching">Matching</option>
                                    <option value="Multiple-Choice Questions">Multiple-Choice Questions</option>
                                    <option value="True/False">True/False</option>
                                    <option value="Short Answer">Short Answer</option>
                                    <option value="Completion">Completion</option>
                                    <option value="Essay">Essay</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-danger" id="save-edit">Save Changes</button>
                    </div>
                </form>
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

    // Handle edit button click
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const topic = this.getAttribute('data-topic');
            const level = this.getAttribute('data-level');
            const items = this.getAttribute('data-items');
            const examType = this.getAttribute('data-exam-type');

            document.getElementById('editTopic').value = topic;
            document.getElementById('editLevel').value = level;
            document.getElementById('editItems').value = items;
            document.getElementById('editExamType').value = examType;
        });
    });

    // Enable all fields before form submission
    function enableFormFields(form) {
        Array.from(form.elements).forEach(function (element) {
            if (element.hasAttribute('disabled')) {
                element.removeAttribute('disabled');
                element.setAttribute('data-disabled', 'true');
            }
        });
    }

    // Disable fields that were initially disabled after form submission
    function disableFormFields(form) {
        Array.from(form.elements).forEach(function (element) {
            if (element.getAttribute('data-disabled') === 'true') {
                element.setAttribute('disabled', 'disabled');
                element.removeAttribute('data-disabled');
            }
        });
    }

    // Handle form submission for editing
    document.getElementById('editForm').addEventListener('submit', function (event) {
        enableFormFields(this);

        event.preventDefault();

        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Exam type updated successfully!'
                }).then(() => {
                    location.reload();
                });
            } else {
                console.error('Failed to update exam type:', data);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update exam type!'
                });
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            disableFormFields(form);
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}'
            });
        @endif

        const form = document.getElementById('uploadForm');
        const loader = document.getElementById('loadingSpinner');

        form.addEventListener('submit', function () {
            loader.style.display = 'block';
        });
    });
</script>

<style>
.topic-section {
    margin-bottom: 30px;
}
.table thead th {
    background-color: #d9534f;
    color: #fff;
}
.table th, .table td {
    vertical-align: middle;
    text-align: center;
}
.topic-title {
    margin-bottom: 10px;
    padding-top: 10px;
    border-top: 1px solid #dee2e6;
}
</style>

@endsection
