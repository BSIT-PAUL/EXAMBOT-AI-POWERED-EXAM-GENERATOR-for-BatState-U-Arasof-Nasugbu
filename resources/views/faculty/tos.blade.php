@extends('layouts.master')
@section('content')
<style>
    .TOS {
        font-family: 'Times New Roman', Times, serif;
        margin: 0 auto;
    }
    .headers {
        display: flex;
        align-items: center;
        text-align: center;        
        margin-left: 15%;
    }
    .headers img {
        width: 120px;
    }
    .border-headers {
        border-bottom: 2px solid #000;
    }
    .headers div {
        text-align: center;
    }
    .headers h1, .headers h2, .headers h3 {
    }
    .thead-signature .p-date{
        text-align: left;
    }
    .headers p {
        margin: 0;
    }
    .BSU {
        font-size: 25px;
    }
    .TNEU {
        color: red;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 15px;
    }
    .assessment {
        font-size: 25px;
    }
    .address .tel {
        font-size: 10px;
    }
    .Exams p {
        text-align: center;
        margin: 0;
    }
    .ROP .campus .emails .SPECIFICATIONS .sem {
        font-size: 12px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        
    }
    th, td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }
    th {
        background-color: #DDEBF7;
        border: 2px solid black;
    }
    br {
        border-bottom: 2px solid #000;
    }
    .Totals th{
        border: 2px solid black;
        background-color: white;
    }
    .signature-section {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        margin-bottom: 40px;
    }
    .signature-section thead tr {
        border: 2px solid black;
        padding: 10px;
    }
    .signature-section th {
        background-color: #d9e2f3;
    }
    .signature-section td {
        background-color: #ffffff;
        text-align: center;
    }
    .add-table-items{
        border: 2px solid black;

    }
    .tbody-signature tr, .tbody-signature td {
        border: none;
        text-align: left;
        
    }
    .first {
        background-color: #ffffff;
        text-align: left;
        font-size: 15px;
    }
    .details {
        text-align: left;
        margin-bottom: 10px;
    }
    .footer span {
        display: block;
        margin-top: 10px;
    }
</style>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Table of Specification</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Table of Specification</a></li>
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
                                    <h3 class="page-title">TOS</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <form class="row align-items-center" method="post" action="{{ route('upload.cis') }}" enctype="multipart/form-data" id="uploadForm">
                                        @csrf
                                        <div class="col">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <button type="submit" class="btn btn-outline-primary me-2">
                                                <i class="fas fa-download"></i> Upload CIS PDF File
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="TOS">
                            <div class="border-headers">
                                <div class="headers">
                                    <img src="{{ URL::to('assets/img/logo-bsu.png') }}" alt="Batangas State University">
                                    <div>
                                        <p class="ROP"><strong>Republic of the Philippines</strong></p>
                                        <p class="BSU"><strong>BATANGAS STATE UNIVERSITY</strong></p>
                                        <p class="TNEU"><strong>The National Engineering University</strong></p>
                                        <p class="campus"><strong>ARASOF-Nasugbu Campus</strong></p>
                                        <p class="address"><strong>R. Martinez St., Brgy. Bucana, Nasugbu, Batangas, Philippines 4231</strong></p>
                                        <p class="tel">Tel. No: +63 917 867 7276</p>
                                        <p class="emails">E-mail Address: gened.nasugbu@g.batstate-u.edu.ph | Website Address: <a href="http://www.batstate-u.edu.ph">www.batstate-u.edu.ph</a></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p><strong>College of Informatics and Computing Sciences Department</strong></p>
                                <div class="Exams">
                                    <p class="SPECIFICATIONS">TABLE OF SPECIFICATIONS</p>
                                    <p class="assessment"><strong>MIDTERM EXAMINATION</strong></p>
                                    <p class="sem">First Semester, Academic Year 2023-2024</p>
                                </div>
                            </div>
                            <div>
                                <p>
                                    <span>Course Code:</span>
                                    <br>
                                    <span>Course Title:</span>
                                </p>
                            </div>
                            <table class="add-table-items">
                                <thead>
                                    <tr>
                                        <th rowspan="2" width=100%>TOPIC</th>
                                        <th colspan="2">No. of Hours</th>
                                        <th rowspan="2">Weight (%)</th>
                                        <th rowspan="2">Remembering</th>
                                        <th rowspan="2">%</th>
                                        <th rowspan="2">Understanding</th>
                                        <th rowspan="2">%</th>
                                        <th rowspan="2">Applying</th>
                                        <th rowspan="2">%</th>
                                        <th rowspan="2">Analyzing</th>
                                        <th rowspan="2">%</th>
                                        <th rowspan="2">Evaluating</th>
                                        <th rowspan="2">%</th>
                                        <th rowspan="2">Creating</th>
                                        <th rowspan="2">%</th>
                                        <th rowspan="2">TOTAL NO. OF POINTS</th>
                                        <th rowspan="2">Actions</th>
                                    </tr>
                                    <tr>
                                        <th>A*</th>
                                        <th>B*</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td contenteditable>Introduction to the Study of Globalization</td>
                                        <td contenteditable>6</td>
                                        <td contenteditable>0.00</td>
                                        <td contenteditable>0.00</td>
                                        <td contenteditable>0</td>
                                        <td contenteditable>0.00</td>
                                        <td contenteditable>0</td>
                                        <td contenteditable>0.00</td>
                                        <td contenteditable>0</td>
                                        <td contenteditable>0.00</td>
                                        <td contenteditable>0</td>
                                        <td contenteditable>0.00</td>
                                        <td contenteditable>0</td>
                                        <td contenteditable>0.00</td>
                                        <td contenteditable>0</td>
                                        <td contenteditable>0.00</td>
                                        <td contenteditable>0</td>
                                        <td class="add-remove">
                                            <a class="add-btn me-2"><i class="fas fa-plus-circle"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                                <thead >
                                    <tr class="Totals">
                                        <th>TOTAL</th>
                                        <th>6</th>
                                        <th>0.00</th>
                                        <th>0.00</th>
                                        <th>0</th>
                                        <th>0.00</th>
                                        <th>0</th>
                                        <th>0.00</th>
                                        <th>0</th>
                                        <th>0.00</th>
                                        <th>0</th>
                                        <th>0.00</th>
                                        <th>0</th>
                                        <th>0.00</th>
                                        <th>0</th>
                                        <th>0.00</th>
                                        <th>0</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="signature-section">
                                <table>
                                    <thead class="thead-signature">
                                        <tr>
                                            <td>
                                                <p class="first">Prepared and Submitted by:</p>
                                                <br><br>
                                                <p>
                                                    <span><strong>{{ Session::get('name') }}</strong></span>
                                                    <br>
                                                    <span>Course Instructor</span>
                                                </p>
                                                <p class="p-date">Date Signed: _______________</p>

                                            </td>
                                            <td>
                                                <p class="first">Checked and Reviewed by:</p>
                                                <br><br>
                                                <p>
                                                    <span><strong>Asst. Prof. BENJIE R. SAMONTE</strong></span>
                                                    <br>
                                                    <span>Program Chairperson, College of Informatics and Computing Sciences Department</span>
                                                </p>
                                                <p class="p-date">Date Signed: _______________</p>
                                            </td>
                                            <td>
                                                <p class="first">Approved:</p>
                                                <br><br>
                                                <p>
                                                    <span><strong>Prof. LORISSA JOANA E. BUENAS</strong></span>
                                                    <br>
                                                    <span>Dean, College of Informatics and Computing Sciences</span>
                                                </p>
                                                <p class="p-date">Date Signed: _______________</p>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-signature">
                                        <td>

                                            <p>
                                                <span> A* - No. of hours the topic was covered in class </span>
                                                <br>
                                                <span> * - No. of hours allotted to answer the test item/s </span>
                                                <br>
                                                <span>**Weight (%) = (no. of points for a given topic /total no. of points) * 100</span>

                                              </p>
                                        </td>
                                    </tbody>
                                </table>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.remove-btn', function() {
        $(this).closest('tr').remove();
        return false;
    });

    $(document).on("click", ".add-btn", function() {
        var newRow = 
        '<tr>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td contenteditable></td>' +
            '<td class="add-remove text-end">' +
                '<a class="add-btn me-2"><i class="fas fa-plus-circle"></i></a>' +
                '<a class="remove-btn btn-outline-danger"><i class="fe fe-trash-2"></i></a>' +
            '</td>' +
        '</tr>';
        $(".add-table-items tbody").append(newRow);
        return false;
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
