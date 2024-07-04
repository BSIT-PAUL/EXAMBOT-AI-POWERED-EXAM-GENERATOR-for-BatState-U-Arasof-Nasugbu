@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="{{url('/')}}/assets/css/tos.css">

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
                                            <input type="file" name="file" class="form-control " required>
                                        </div>
                                        
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <button type="submit" class="btn btn-outline-danger me-2">
                                                <i class="fas fa-download"></i> Upload CIS PDF File
                                            </button>
                                            <a class="btn btn-outline-danger me-2" href="#" data-bs-toggle="modal" data-bs-target="#bank_details" id="btn-add-bank-details"><i class="fas fa-plus-circle me-2"></i>Generate TOS</a>
                                            <a class="btn btn-outline-danger" href="#" data-bs-toggle="modal" data-bs-target="#invoices_preview"><i class="fa fa-print"></i> Print </a>
                                            
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
                                        <p class="emails">E-mail Address: cics.nasugbu@g.batstate-u.edu.ph | Website Address: <a href="http://www.batstate-u.edu.ph">www.batstate-u.edu.ph</a></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p><strong>College of Informatics and Computing Sciences Department</strong></p>
                                <div class="Exams">
                                    <p class="SPECIFICATIONS">TABLE OF SPECIFICATIONS</p>
                                    <p class="assessment">
                                        @if(isset($TOSinfo) && count($TOSinfo) > 0)
    @php
        $examinationType = $TOSinfo->first()->examination_type;
    @endphp
@endif
                                        
                                        @if(isset($examinationType))
                                        {{ $examinationType }}
                                    @endif</p>
                                    
                                    <p class="sem">
                                        @if(isset($courseInfo))
                                        <?php
                                                $semesterYear = trim($courseInfo->semester_year);
                                                // Extract the part before " / "
                                                $semester = explode(' / ', $semesterYear)[0];
                                            ?>
                                            {{ $semester  }}
                                        @endif, Academic Year <?php echo date('Y') ; ?>-<?php echo (date('Y')+1); ?>
                                    </p>
                                </div>
                            </div>
                            <div>   
                                @if(isset($courseInfo))
                                <p>
                                        <span>Course Code: <strong>{{ $courseInfo->course_code }}</strong></span>
                                        <br>
                                        <span>Course Title: <strong>{{ $courseInfo->course_title }}</strong></span>
                                    </p>
                                @else
                                    <span>Course Code: <strong></strong></span>
                                    <br>
                                    <span>Course Title: <strong></strong></span>
                                @endif
                            </div>
                            <table class="add-table-items">
                                <thead>
                                    <tr>
                                        <th rowspan="2" width="100%">TOPIC</th>
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
                                    </tr>
                                    <tr>
                                        <th>A*</th>
                                        <th>B*</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($TOSinfo as $info)
                                    <tr>
                                        <td>{{ $info->topic ?? '' }}</td>
                                        <td>{{ $info->no_of_hours_a ?? '' }}</td>
                                        <td>{{ number_format($info->no_of_hours_b ?? 0, 2) }}</td>
                                        <td>{{ number_format($info->weight ?? 0, 2) }}</td>
                                        <td>{{ $info->remembering ?? '' }}</td>
                                        <td>{{ number_format($info->remembering_percentage  ?? 0, 2) }}</td>
                                        <td>{{ $info->understanding ?? '' }}</td>
                                        <td>{{ number_format($info->understanding_percentage  ?? 0, 2) }}</td>
                                        <td>{{ $info->applying ?? '' }}</td>
                                        <td>{{ number_format($info->applying_percentage  ?? 0, 2) }}</td>
                                        <td>{{ $info->analyzing ?? '' }}</td>
                                        <td>{{ number_format($info->analyzing_percentage  ?? 0, 2)}}</td>
                                        <td>{{ $info->evaluating ?? '' }}</td>
                                        <td>{{ number_format($info->evaluating_percentage  ?? 0, 2) }}</td>
                                        <td>{{ $info->creating ?? '' }}</td>
                                        <td>{{ number_format($info->creating_percentage  ?? 0, 2) }}</td>
                                        <td>{{ $info->total_no_of_points ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                
                                <thead>
                                    <tr class="Totals">
                                        <th>TOTAL</th>
                                        <th>{{ $sum_of_hours_a  ?? ''  }}</th> <!-- Display the sum of no_of_hours_a -->
                                        <th>{{ $sum_of_hours_b  ?? '' }}</th> <!-- Display the sum of no_of_hours_b -->
                                        <th>{{ $sum_of_weight  ?? '' }}</th> <!-- Display the sum of weight -->
                                        <th>{{ $sum_of_remembering  ?? '' }}</th>
                                        <th>{{ $sum_of_remembering_percentage  ?? '' }}</th>
                                        <th>{{ $sum_of_understanding  ?? '' }}</th>
                                        <th>{{ $sum_of_understanding_percentage  ?? '' }}</th>
                                        <th>{{ $sum_of_applying  ?? '' }}</th>
                                        <th>{{ $sum_of_applying_percentage  ?? '' }}</th>
                                        <th>{{ $sum_of_analyzing  ?? '' }}</th>
                                        <th>{{ $sum_of_analyzing_percentage  ?? '' }}</th>
                                        <th>{{ $sum_of_evaluating  ?? '' }}</th>
                                        <th>{{ $sum_of_evaluating_percentage  ?? '' }}</th>
                                        <th>{{ $sum_of_creating  ?? '' }}</th>
                                        <th>{{ $sum_of_creating_percentage  ?? '' }}</th>
                                        <th>   @if(isset($TOSinfo) && count($TOSinfo) > 0)   @php  $overall_points = $TOSinfo->first()->overall_points;  @endphp     @endif {{$overall_points ?? '' }}
                                        </th>
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


<div class="modal custom-modal fade bank-details" id="bank_details" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="form-header text-start mb-0">
                    <h4 class="mb-0">Add Topic</h4>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="topicForm" method="POST" action="{{ route('save_topic') }}">
                    @csrf
                    <div class="bank-inner-details">
                        <div>
                            @if(isset($courseInfo))
                            <input type="hidden" name="course_code" value="{{ $courseInfo->course_code}}">
                            @endif
                            <div class="row">
                                <div class="col-lg-7 col-md-8">

                                    <div class="form-group">
                                <label>Assesment</label>
                                <select name="assessment_type" class="form-select form-control topic-select">
                                    <option value="" disabled selected>Select Assessment Type</option>
                                    <option>MIDTERM EXAMINATION</option>
                                    <option>FINAL EXAMINATION</option>
                                </select>
                            </div>
                            </div>                            
                            <div class="col-lg-5 col-md-5">
                                <div class="form-group">
                                    <label>Total Item</label>
                                    <input type="number" id="totalItems" class="form-control" name="overall_points" placeholder="Overall total points" required>
                                </div>
                            </div>

                            </div>
                           
                        </div>
                        <div id="topicContainer">
                            <div class="row topicRow">
                                <div class="col-lg-5 col-md-5">
                                    <div class="form-group">
                                        <label>Topic</label>
                                        <select name="main_topic[]" class="form-select form-control topic-select">
                                            <option value="" disabled selected>Select a topic</option>
                                            @foreach($courseChapters as $chapter)
                                            <option value="{{ $chapter->main_topic }}">{{ $chapter->main_topic }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <div class="form-group">
                                        <label>Hours</label>
                                        <input type="number" class="form-control" name="no_of_hours[]" placeholder="No. of hours the topic was covered in class" required>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-3">
                                    <label>Action</label>
                                    <div>
                                        <a class="btn btn-outline-danger addTopicButton"><i class="fas fa-plus-circle me-2"></i>Add Topic </a>
                                        <a class="btn btn-outline-danger removeTopicButton"><i class="fe fe-trash-2 me-2"></i>Remove </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="">
                            <a  data-bs-dismiss="modal" class="btn btn-outline-danger me-2">Cancel</a>
                            <button type="submit" class="btn btn-outline-danger me-2" name="save-tos" id="save-tos">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>

document.addEventListener('DOMContentLoaded', function() {
    // Add Topic Button
    document.getElementById('topicContainer').addEventListener('click', function(event) {
        if (event.target && event.target.closest('.addTopicButton')) {
            // Clone the topic row
            var topicRow = document.querySelector('.topicRow');
            var clone = topicRow.cloneNode(true);

            // Clear the values in the cloned inputs
            var inputs = clone.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].value = '';
            }

            var selects = clone.getElementsByTagName('select');
            for (var i = 0; i < selects.length; i++) {
                selects[i].selectedIndex = 0;
            }

            // Append the cloned row to the container
            document.getElementById('topicContainer').appendChild(clone);

            // Update the topic select options
            updateTopicOptions();
        }
    });

    // Remove Topic Button
    document.getElementById('topicContainer').addEventListener('click', function(event) {
        if (event.target && event.target.closest('.removeTopicButton')) {
            var rows = document.querySelectorAll('.topicRow');
            if (rows.length > 1) {
                event.target.closest('.topicRow').remove();
                updateTopicOptions();
            }
        }
    });

    // Update topic options based on selected topics
    function updateTopicOptions() {
        var allSelects = document.querySelectorAll('.topic-select');
        var selectedTopics = [];

        allSelects.forEach(function(select) {
            if (select.value) {
                selectedTopics.push(select.value);
            }
        });

        allSelects.forEach(function(select) {
            var options = select.querySelectorAll('option');
            options.forEach(function(option) {
                if (selectedTopics.includes(option.value) && option.value !== select.value) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        });
    }

    // Handle change event on topic selects to update options dynamically
    document.getElementById('topicContainer').addEventListener('change', function(event) {
        if (event.target && event.target.matches('.topic-select')) {
            updateTopicOptions();
        }
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
@endsection
