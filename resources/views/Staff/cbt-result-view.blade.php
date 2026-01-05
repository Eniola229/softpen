@include('components.header')
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<style>
    .result-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .stat-card {
        border-left: 4px solid;
        padding: 20px;
        margin-bottom: 15px;
    }
    .stat-card.correct { border-color: #28a745; background-color: #d4edda; }
    .stat-card.wrong { border-color: #dc3545; background-color: #f8d7da; }
    .stat-card.unanswered { border-color: #ffc107; background-color: #fff3cd; }
    .answer-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .answer-card.correct {
        border-left: 5px solid #28a745;
        background-color: #f8fff9;
    }
    .answer-card.wrong {
        border-left: 5px solid #dc3545;
        background-color: #fff8f8;
    }
    .option-label {
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 8px;
        display: block;
    }
    .option-label.selected {
        background-color: #e3f2fd;
        border: 2px solid #2196F3;
    }
    .option-label.correct {
        background-color: #d4edda;
        border: 2px solid #28a745;
    }
    .option-label.wrong {
        background-color: #f8d7da;
        border: 2px solid #dc3545;
    }
</style>
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
        @include('components.staff-nav')

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">CBT Exam Result Details</h4>
                        <div class="ms-auto text-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Staff</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('staff/view/student/', $student->id) }}">{{ $student->name }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Exam Result</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <!-- Result Header -->
                <div class="result-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2>{{ $examResult->exam->title }}</h2>
                            <h4>{{ $student->name }} - {{ $student->class }}</h4>
                            <p class="mb-0">{{ $subject ? $subject->name : 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <h1 class="display-3">{{ number_format($examResult->percentage, 1) }}%</h1>
                            @if($examResult->percentage >= $examResult->exam->passing_score)
                                <h4><span class="badge bg-success">PASSED</span></h4>
                            @else
                                <h4><span class="badge bg-danger">FAILED</span></h4>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card correct">
                            <h3 class="text-success">{{ $examResult->studentAnswers->where('is_correct', true)->count() }}</h3>
                            <p class="mb-0"><strong>Correct Answers</strong></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card wrong">
                            <h3 class="text-danger">{{ $examResult->studentAnswers->where('is_correct', false)->where('selected_option_id', '!=', null)->count() }}</h3>
                            <p class="mb-0"><strong>Wrong Answers</strong></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card unanswered">
                            <h3 class="text-warning">{{ $examResult->studentAnswers->whereNull('selected_option_id')->count() }}</h3>
                            <p class="mb-0"><strong>Unanswered</strong></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3>{{ $examResult->total_score }} / {{ $examResult->studentAnswers->sum('question.mark') }}</h3>
                                <p class="mb-0"><strong>Total Score</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exam Details -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Exam Information</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Duration:</strong> {{ $examResult->exam->duration }} minutes</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Total Questions:</strong> {{ $examResult->exam->total_questions }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Passing Score:</strong> {{ $examResult->exam->passing_score }}%</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Started:</strong> {{ $examResult->started_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Submitted:</strong> {{ $examResult->submitted_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Time Taken:</strong> {{ $examResult->started_at->diff($examResult->submitted_at)->format('%i min %s sec') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions and Answers -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Detailed Answer Review</h5>

                        @foreach($examResult->studentAnswers as $index => $answer)
                        <div class="answer-card {{ $answer->is_correct ? 'correct' : ($answer->selected_option_id ? 'wrong' : '') }}">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5>
                                    Question {{ $index + 1 }}
                                    @if($answer->is_correct)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Correct</span>
                                    @elseif($answer->selected_option_id)
                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Wrong</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-minus"></i> Unanswered</span>
                                    @endif
                                </h5>
                                <span class="badge bg-primary">{{ $answer->marks_obtained }} / {{ $answer->question->mark }} pts</span>
                            </div>

                            <div class="mb-3">
                                <p class="mb-2"><strong>{!! $answer->question->question_text !!}</strong></p>
                                @if($answer->question->question_image)
                                    <img src="{{ $answer->question->question_image }}" alt="Question" style="max-width: 400px; border-radius: 8px;">
                                @endif
                            </div>

                            <div class="ms-3">
                                <h6 class="mb-2">Options:</h6>
                                @foreach($answer->question->options as $option)
                                <div class="option-label 
                                    {{ $option->id == $answer->selected_option_id && $option->is_correct ? 'correct selected' : '' }}
                                    {{ $option->id == $answer->selected_option_id && !$option->is_correct ? 'wrong selected' : '' }}
                                    {{ $option->id != $answer->selected_option_id && $option->is_correct ? 'correct' : '' }}">
                                    
                                    <div class="d-flex align-items-start">
                                        <strong style="min-width: 30px;">{{ chr(65 + $loop->index) }}.</strong>
                                        <div class="flex-grow-1">
                                            {{ $option->option_text }}
                                            
                                            @if($option->id == $answer->selected_option_id)
                                                <span class="badge bg-primary ms-2">Student's Answer</span>
                                            @endif
                                            
                                            @if($option->is_correct)
                                                <span class="badge bg-success ms-2"><i class="fas fa-check"></i> Correct Answer</span>
                                            @endif
                                            
                                            @if($option->option_image)
                                                <br><img src="{{ $option->option_image }}" alt="Option" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if(!$answer->selected_option_id)
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fas fa-info-circle"></i> Student did not answer this question
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <a href="{{ route('staff/view/student/', $student->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Student Profile
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Print Result
                        </button>
                    </div>
                </div>
            </div>

            <footer class="footer text-center">
                All Rights Reserved by SoftPenTech | Developed by SoftpenTech
            </footer>
        </div>
    </div>

<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('dist/js/waves.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<!-- this page js -->
<script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
<script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
<script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>