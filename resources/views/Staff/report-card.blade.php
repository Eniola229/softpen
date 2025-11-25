@include('components.header') 
    <link
      rel="stylesheet"
      type="text/css"
      href="../assets/extra-libs/multicheck/multicheck.css"
    />
    <link
      href="../assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
      rel="stylesheet"
    />
    <link href="../dist/css/style.min.css" rel="stylesheet" />
  <body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div> -->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      @include('components.nav') 
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      @include('components.staff-nav') 
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title"></h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Students
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->

          
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

<style>
    .report-container {
        max-width: 900px;
        margin: 20px auto;
        background-color: white;
        padding: 30px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        padding-bottom: 20px;
    }

    .header-left {
        flex: 1;
    }

    .header-center {
        flex: 2;
        text-align: center;
    }

    .header-right {
        flex: 1;
        text-align: right;
    }

    .school-logo {
        max-height: 80px;
        width: auto;
        margin-bottom: 10px;
    }

    .school-info-table {
        font-size: 14px;
        color: #555;
        text-align: left;
        line-height: 1.6;
        margin-bottom: 0px;
        margin-top: 10px;
    }

    .school-info-table div {
        margin-bottom: 3px;
    }

    .school-info-label {
        font-weight: bold;
        color: #333;
        display: inline-block;
        width: 80px;
    }

    .school-name {
        font-size: 27px;
        font-weight: 900;
        color: #003366;
        margin-bottom: 3px;
        letter-spacing: 1px;
    }

    .school-tagline {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
        font-style: italic;
    }

    .school-address {
        font-size: 13px;
        color: #555;
        margin-bottom: 8px;
    }
    
    .school-mobile {
        font-size: 13px;
        color: #555;
        margin-bottom: 8px;
    }

    .school-contact {
        font-size: 9px;
        color: #555;
    }

    .report-title {
        font-size: 23px;
        font-weight: bold;
        color: #d32f2f;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 0px;
    }

    .student-avatar {
        max-height: 90px;
        max-width: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ddd;
    }

    .print-btn {
        margin-bottom: 20px;
    }

    .print-btn button {
        background-color: #003366;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .print-btn button:hover {
        background-color: #00509e;
    }

    .print-btn button:active {
        transform: scale(0.98);
    }

    @media print {
        .print-btn {
            display: none;
        }
    }

    .student-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        font-size: 11px;
        flex-wrap: wrap;
    }

    .student-info-item {
        flex: 1;
        min-width: 150px;
        margin-bottom: 10px;
    }

    .student-info-label {
        font-weight: bold;
        color: #333;
    }

    .student-info-value {
        color: #666;
        margin-top: 2px;
    }

    .section-title {
        font-size: 12px;
        font-weight: bold;
        color: #333;
        margin: 25px 0 12px 0;
        text-transform: uppercase;
        border-bottom: 1px solid #999;
        padding-bottom: 5px;
    }

    /* TABLE STYLING */
    .results-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        font-size: 12px;
    }

    .results-table thead {
        background-color: #f0f0f0;
        border: 1px solid #999;
    }

    .results-table thead th {
        padding: 8px;
        text-align: center;
        border: 1px solid #999;
        font-weight: bold;
        color: #333;
        font-size: 11px;
    }

    .results-table tbody td {
        padding: 8px;
        border: 1px solid #999;
        text-align: center;
        height: 30px;
    }

    .results-table tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    .results-table .subject-name {
        text-align: left;
        font-weight: 500;
        color: #333;
        padding-left: 12px;
    }

    .results-table tbody td:first-child {
        text-align: center;
        width: 35px;
        font-weight: bold;
    }

    /* SUMMARY */
    .summary {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 30px;
        font-size: 11px;
    }

    .summary-box {
        border: 1px solid #999;
        padding: 12px;
        text-align: center;
        background-color: #f9f9f9;
    }

    .summary-label {
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .summary-value {
        font-size: 16px;
        font-weight: bold;
        color: #d32f2f;
    }

    /* COMMENTS */
    .comments {
        margin-bottom: 20px;
        font-size: 11px;
    }

    .comment-box {
        margin-bottom: 12px;
    }

    .comment-label {
        font-weight: bold;
        color: #333;
        margin-bottom: 4px;
    }

    .comment-text {
        color: #666;
        min-height: 40px;
        padding: 8px;
        border: 1px solid #ddd;
        background-color: #fafafa;
        line-height: 1.4;
    }

    /* FOOTER */
    .footer {
        text-align: right;
        font-size: 10px;
        color: #666;
        margin-top: 30px;
        padding-top: 15px;
        border-top: 1px solid #ddd;
    }

    .signature-line {
        margin-top: 15px;
    }

    @media print {
        body {
            background-color: white;
            padding: 0;
        }
        .report-container {
            box-shadow: none;
            max-width: 100%;
            margin: 0;
        }
    }
</style>

<div class="print-btn">
    <button onclick="window.print()">🖨️ Print Report Card</button>
</div>

<div class="report-container">
    <!-- HEADER -->
    <div class="header">
        <!-- LEFT: School Logo & Info -->
        <div class="header-left">
            @if($school->avatar)
                <img src="{{ $school->avatar }}" alt="School Logo" class="school-logo">
            @endif
            <div class="school-info-table">
                <div><span class="school-info-label">NAME:</span> <strong>{{ strtoupper($student->name) }}</strong></div>
                <div><span class="school-info-label">CLASS:</span> {{ $student->class }}</div>
                <div><span class="school-info-label">TERM:</span> {{ strtoupper($term) }}</div>
                <div><span class="school-info-label">SESSION:</span> {{ strtoupper($session) }}</div>
                </div>
        </div>

        <!-- CENTER: School Name & Title -->
        <div class="header-center">
            <div class="school-name">{{ strtoupper($school->name) }}</div>
            <div class="school-tagline"><strong>Address:</strong> {{ $school->address }}</div>
            <div class="school-address">
                <strong>Motto:</strong> {{ $school->motto }} <br>
            </div>
           <!--  <div class="school-mobile">
                Telephone: {{ $school->mobile }} | Email: {{ $school->email }}<br>
            </div> -->
        </div>

        <!-- RIGHT: Student Avatar -->
        <div class="header-right">
            @if($student->avatar)
                <img src="{{ $student->avatar }}" alt="Student Photo" class="student-avatar">
            @else
                <div style="width: 90px; height: 90px; border-radius: 50%; background-color: #ddd; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <span style="color: #999; font-size: 12px;">No Photo</span>
                </div>
            @endif
        </div>
    </div>

    <!-- STUDENT INFO -->
    <!-- <div class="student-info">
        <div class="student-info-item">
            <div class="student-info-label">STUDENT NAME:</div>
            <div class="student-info-value">{{ strtoupper($student->name) }}</div>
        </div>
        <div class="student-info-item">
            <div class="student-info-label">CLASS:</div>
            <div class="student-info-value">{{ $student->class }}</div>
        </div>
        <div class="student-info-item">
            <div class="student-info-label">SESSION:</div>
            <div class="student-info-value">{{ $session }}</div>
        </div>
        <div class="student-info-item">
            <div class="student-info-label">TERM:</div>
            <div class="student-info-value">{{ strtoupper($term) }}</div>
        </div>
    </div> -->

    <!-- RESULTS TABLE -->
    <!-- <div class="section-title">{{ strtoupper($term) }} - RESULTS</div> -->
    <div class="report-title">
    @php
        $levelFull = match(strtoupper($level)) {
            'SSS' => 'SENIOR SECONDARY SCHOOL',
            'JS', 'JSS' => 'JUNIOR SECONDARY SCHOOL',
            default => strtoupper($level)
        };
    @endphp
    TERMLY REPORT FOR 
    {{ $levelFull }}
    </div>

    {{-- FIRST AND SECOND TERM: Simple Table --}}
    @if($term !== 'Third Term')
        <table class="results-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>SUBJECTS</th>
                    <th>Test (40)</th>
                    <th>Exam (60)</th>
                    <th>Total (100)</th>
                    <th>Grade</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $subjectId => $score)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="subject-name">{{ $score['name'] }}</td>
                        <td>{{ $score['test'] ?? 0 }}</td>
                        <td>{{ $score['exam'] ?? 0 }}</td>
                        <td><strong>{{ $score['total'] ?? 0 }}</strong></td>
                        <td><strong>{{ $score['grade'] ?? '-' }}</strong></td>
                        <td>
                            @if($score['total'] >= 70)
                                Excellent
                            @elseif($score['total'] >= 60)
                                Good
                            @elseif($score['total'] >= 50)
                                Fair
                            @else
                                Poor
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No results available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    {{-- THIRD TERM: Extended Table with All Terms --}}
    @else
        <table class="results-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>SUBJECTS</th>
                    <th colspan="2">1st Term</th>
                    <th colspan="2">2nd Term</th>
                    <th colspan="2">3rd Term</th>
                    <th>Grade</th>
                    <th>Remark</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Test (10)</th>
                    <th>Exam (90)</th>
                    <th>Test (10)</th>
                    <th>Exam (90)</th>
                    <th>Test (10)</th>
                    <th>Exam (90)</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $subjectId => $score)
                    @php
                        $t1 = $cumulative[$subjectId]['t1'] ?? 0;
                        $t2 = $cumulative[$subjectId]['t2'] ?? 0;
                        $t3 = $cumulative[$subjectId]['t3'] ?? 0;
                        $avg = $cumulative[$subjectId]['average'] ?? 0;
                        $grade = $cumulative[$subjectId]['grade'] ?? '-';
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="subject-name">{{ $score['name'] }}</td>
                        
                        {{-- 1st Term --}}
                        @if($t1)
                            <td>{{ $firstTerm->scores[$subjectId]['test'] ?? 0 }}</td>
                            <td>{{ $firstTerm->scores[$subjectId]['exam'] ?? 0 }}</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                        @endif

                        {{-- 2nd Term --}}
                        @if($t2)
                            <td>{{ $secondTerm->scores[$subjectId]['test'] ?? 0 }}</td>
                            <td>{{ $secondTerm->scores[$subjectId]['exam'] ?? 0 }}</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                        @endif

                        {{-- 3rd Term (Current) --}}
                        <td>{{ $score['test'] ?? 0 }}</td>
                        <td>{{ $score['exam'] ?? 0 }}</td>

                        {{-- Grade & Remark --}}
                        <td><strong>{{ $grade }}</strong></td>
                        <td>
                            @if($avg >= 70)
                                Excellent
                            @elseif($avg >= 60)
                                Good
                            @elseif($avg >= 50)
                                Fair
                            @else
                                Poor
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No results available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

        <!-- AFFECTIVE DISPOSITION & PSYCHOMOTOR SKILLS RATING TABLE -->
    <div style="margin-top: 30px; margin-bottom: 30px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- LEFT: Affective Disposition Rating -->
            <div>
                <h6 style="font-size: 12px; font-weight: bold; color: #333; margin-bottom: 12px; text-transform: uppercase;">Affective Disposition Rating</h6>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr>
                            <th style="padding: 8px; border: 1px solid #999; text-align: left; background-color: #f0f0f0; font-weight: bold;">Trait</th>
                            <th style="padding: 8px; border: 1px solid #999; text-align: center; background-color: #f0f0f0; font-weight: bold; width: 50px;">Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Attentiveness</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Cooperation with others</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Emotional Stability</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Honesty</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Leadership</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Neatness</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Politeness</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Punctuality</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- RIGHT: Psychomotor Skills & Rating Key -->
            <div>
                <h6 style="font-size: 12px; font-weight: bold; color: #333; margin-bottom: 12px; text-transform: uppercase;">Psychomotor Skills</h6>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 20px;">
                    <thead>
                        <tr>
                            <th style="padding: 8px; border: 1px solid #999; text-align: left; background-color: #f0f0f0; font-weight: bold;">Skill</th>
                            <th style="padding: 8px; border: 1px solid #999; text-align: center; background-color: #f0f0f0; font-weight: bold; width: 50px;">Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Drawing & Painting</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Handing Tools</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Handwriting</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Music</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Sports</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                    </tbody>
                </table>

                <h6 style="font-size: 12px; font-weight: bold; color: #333; margin-bottom: 12px; text-transform: uppercase;">Rating Key</h6>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <tbody>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Excellent</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">5</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Very Good</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">4</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Good</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">3</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Poor</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">2</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #999; text-align: left;">Very Poor</td>
                            <td style="padding: 8px; border: 1px solid #999; text-align: center;">1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <div class="summary-box">
            <div class="summary-label">Total Score</div>
            <div class="summary-value">{{ $totalScore }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Termly Average</div>
            <div class="summary-value">{{ $average }}</div>
        </div>
    </div>

    <!-- COMMENTS -->
    <div class="comments">
        <div class="comment-box">
            <div class="comment-label">CLASS TEACHER'S COMMENT:</div>
            <div class="comment-text">{{ $teachersComment ?? 'No comment yet.' }}</div>
        </div>
        <div class="comment-box">
            <div class="comment-label">PRINCIPAL'S COMMENT:</div>
            <div class="comment-text">{{ $principalComment ?? 'No comment yet.' }}</div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div style="margin-bottom: 40px;">
            Date: ________________
        </div>
        <div class="signature-line">
            _________________________<br>
            Principal/School Head
        </div>
    </div>
</div>



          <!-- ============================================================== -->
          <!-- End PAge Content -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- Right sidebar -->
          <!-- ============================================================== -->
          <!-- .right-sidebar -->
          <!-- ============================================================== -->
          <!-- End Right sidebar -->
          <!-- ============================================================== -->
        </div>

     
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            All Rights Reserved by SoftPen Technologies | Deleoped by Softpen Tech
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- this page js -->
    <script src="../assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="../assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="../assets/extra-libs/DataTables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
      /****************************************
       *       Basic Table                   *
       ****************************************/
      $("#zero_config").DataTable();
    </script>
    <script type="text/javascript">
      function confirmStatusChange(url) {
          Swal.fire({
              title: 'Are you sure?',
              text: "You are about to delete this schol.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = url; // Redirect to the link URL
              }
          });
      }
    </script>
  </body>
</html>
