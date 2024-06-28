@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Result Management</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Result</a></li>
                        <li class="breadcrumb-item active">View</li>
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
                                    <h3 class="page-title">Exam Result</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
            
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <button type="button" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Print Result</button>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            {{-- <table
                                class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" value="something">
                                            </div>
                                        </th>
                                        <th>SR-CODE</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Score</th>
                                        <th>Block</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                    <td class="text-end">
                                        <button class="btn btn-danger btn-sm delete-file-btn" >
                                                <i class="far fa-trash-alt me-2"></i>Delete
                                        </button>
                                    </td>
                                </tbody>
                            </table> --}}
                            <table
                                class="table star-student table-hover table-center table-borderless table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>SR-CODE</th>
                                        <th>Name</th>
                                        <th class="text-center">Score</th>
                                        <th class="text-center">Percentage</th>
                                        <th class="text-end">Block</th>
                                        <th class="text-center">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-nowrap">
                                            <div>21-77515</div>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="profile.html">
                                                <img class="rounded-circle"src="{{ URL::to('assets/img/profiles/avatar-02.jpg') }}" width="25" alt="Star Students"> John Paul
                                            </a>
                                        </td>
                                        <td class="text-center">49.00</td>
                                        <td class="text-center">98.00%</td>
                                        <td class="text-end">
                                            <div>IT-BA 3202</div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm delete-file-btn" >
                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-nowrap">
                                            <div>21-77515</div>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="profile.html">
                                                <img class="rounded-circle"src="{{ URL::to('assets/img/profiles/avatar-02.jpg') }}" width="25" alt="Star Students"> John Paul
                                            </a>
                                        </td>
                                        <td class="text-center">49.75</td>
                                        <td class="text-center">99.50%</td>
                                        <td class="text-end">
                                            <div>IT-BA 3202</div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm delete-file-btn" >
                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-nowrap">
                                            <div>21-77515</div>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="profile.html">
                                                <img class="rounded-circle"src="{{ URL::to('assets/img/profiles/avatar-02.jpg') }}" width="25" alt="Star Students"> John Paul
                                            </a>
                                        </td>
                                        <td class="text-center">49.80</td>
                                        <td class="text-center">99.60%</td>
                                        <td class="text-end">
                                            <div>IT-BA 3202</div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm delete-file-btn" >
                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-nowrap">
                                            <div>21-77515</div>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="profile.html">
                                                <img class="rounded-circle"src="{{ URL::to('assets/img/profiles/avatar-02.jpg') }}" width="25" alt="Star Students"> John Paul
                                            </a>
                                        </td>
                                        <td class="text-center">49.10</td>
                                        <td class="text-center">98.20%</td>
                                        <td class="text-end">
                                            <div>IT-BA 3202</div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm delete-file-btn" >
                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-nowrap">
                                            <div>21-77515</div>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="profile.html">
                                                <img class="rounded-circle"src="{{ URL::to('assets/img/profiles/avatar-02.jpg') }}" width="25" alt="Star Students"> John Paul
                                            </a>
                                        </td>
                                        <td class="text-center">49.00</td>
                                        <td class="text-center">98.00%</td>
                                        <td class="text-end">
                                            <div>IT-BA 3202</div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm delete-file-btn" >
                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>



                      
                        {{-- <div class="table-responsive">
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
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
