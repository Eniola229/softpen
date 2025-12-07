@include('components.header')
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" 
         data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        
        @include('components.nav')
        @include('components.student-nav')

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="min-height: 80vh;">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="card shadow-lg" style="max-width: 600px; width: 100%;">
                            <div class="card-body text-center p-5">
                                <div class="mb-4">
                                    <i class="fas fa-clock text-danger" style="font-size: 80px;"></i>
                                </div>
                                <h2 class="text-danger mb-3">Sorry, You Are Late!</h2>
                                <p class="lead mb-4">
                                    The exam "{{ $activeExam->title }}" has already started and you have exceeded the grace period.
                                </p>
                                <div class="alert alert-warning">
                                    <strong>Exam Details:</strong><br>
                                    <p class="mb-1"><strong>Subject:</strong> {{ $activeExam->subject }}</p>
                                    <p class="mb-1"><strong>Start Time:</strong> {{ Carbon\Carbon::parse($activeExam->exam_date_time)->format('M d, Y h:i A') }}</p>
                                    <p class="mb-0"><strong>Duration:</strong> {{ $activeExam->duration }} minutes</p>
                                </div>
                                <p class="text-muted">
                                    Students must arrive within 10 minutes of the scheduled start time to take the exam.
                                </p>
                                <a href="{{ route('student-dashboard') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-home"></i> Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer text-center">
                All Rights Reserved by SoftPenTech | Developed by SoftpenTech
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
</body>
</html>