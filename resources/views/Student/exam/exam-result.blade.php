@include('components.header') 
<link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

<style>
    /* Custom styling for result page */
    .result-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px 0;
        border-radius: 10px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .result-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.3;
        animation: float 20s linear infinite;
    }
    
    @keyframes float {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .percentage-circle {
        width: 120px;
        height: 120px;
        background: conic-gradient(#28a745 {{ $examResult->percentage }}%, #e9ecef 0%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .percentage-circle::before {
        content: '';
        width: 90px;
        height: 90px;
        background: white;
        border-radius: 50%;
        position: absolute;
    }
    
    .percentage-text {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        z-index: 1;
        position: relative;
    }
    
    .grade-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 1.1rem;
        margin: 10px 0;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .grade-A { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .grade-B { background: linear-gradient(135deg, #17a2b8, #20c997); color: white; }
    .grade-C { background: linear-gradient(135deg, #ffc107, #fd7e14); color: #212529; }
    .grade-D { background: linear-gradient(135deg, #dc3545, #fd7e14); color: white; }
    .grade-F { background: linear-gradient(135deg, #6c757d, #495057); color: white; }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
        border-top: 4px solid #667eea;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card i {
        font-size: 2rem;
        color: #667eea;
        margin-bottom: 10px;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        margin: 10px 0;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .question-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .question-container.correct {
        border-left-color: #28a745;
        background: linear-gradient(to right, rgba(40, 167, 69, 0.05), white);
    }
    
    .question-container.incorrect {
        border-left-color: #dc3545;
        background: linear-gradient(to right, rgba(220, 53, 69, 0.05), white);
    }
    
    .question-number {
        display: inline-block;
        width: 30px;
        height: 30px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        font-weight: bold;
        margin-right: 10px;
    }
    
    .answer-status {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-left: 10px;
    }
    
    .answer-status.correct {
        background: #d4edda;
        color: #155724;
    }
    
    .answer-status.incorrect {
        background: #f8d7da;
        color: #721c24;
    }
    
    .option-indicator {
        display: inline-block;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        text-align: center;
        line-height: 25px;
        font-weight: bold;
        margin-right: 10px;
        font-size: 0.8rem;
    }
    
    .option-indicator.selected {
        background: #007bff;
        color: white;
    }
    
    .option-indicator.correct {
        background: #28a745;
        color: white;
    }
    
    .option-indicator.not-selected {
        background: #e9ecef;
        color: #6c757d;
    }
    
    .wizard > .steps > ul > li.current > a {
        background: #667eea !important;
        color: white !important;
    }
    
    .wizard > .steps > ul > li.done > a {
        background: #28a745 !important;
        color: white !important;
    }
    
    .wizard > .content {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 30px;
        margin-top: 20px;
        min-height: 400px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        justify-content: center;
    }
    
    .btn-download {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-print {
        background: linear-gradient(135deg, #20c997, #17a2b8);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(32, 201, 151, 0.4);
        color: white;
    }
    
    .score-breakdown {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin: 20px 0;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    
    .score-bar {
        flex-grow: 1;
        height: 20px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin: 0 20px;
        position: relative;
    }
    
    .score-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 10px;
        width: {{ $examResult->percentage }}%;
        transition: width 1.5s ease-in-out;
    }
    
    .time-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-top: 20px;
        border-left: 4px solid #17a2b8;
    }
</style>

<body>
<div class="preloader">
  <div class="lds-ripple"><div class="lds-pos"></div><div class="lds-pos"></div></div>
</div>

<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" 
     data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
  @include('components.nav')
  @include('components.student-nav')

  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">
            <i class="fas fa-chart-line me-2"></i>Exam Result: {{ $examResult->exam->title }}
          </h4>
          <div class="ms-auto">
            <span class="badge bg-light text-dark">
              <i class="fas fa-calendar me-1"></i>
              {{ \Carbon\Carbon::parse($examResult->submitted_at)->format('M d, Y h:i A') }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <!-- Result Header -->
      <div class="result-header">
        <div class="row align-items-center">
          <div class="col-md-4 text-center">
            <div class="percentage-circle">
              <span class="percentage-text">{{ round($examResult->percentage, 1) }}%</span>
            </div>
            @php
                $grade = '';
                if ($examResult->percentage >= 80) $grade = 'A';
                elseif ($examResult->percentage >= 70) $grade = 'B';
                elseif ($examResult->percentage >= 60) $grade = 'C';
                elseif ($examResult->percentage >= 50) $grade = 'D';
                else $grade = 'F';
            @endphp
            <div class="grade-badge grade-{{ $grade }}">GRADE: {{ $grade }}</div>
          </div>
          <div class="col-md-8">
            <h1 class="text-white">{{ $examResult->exam->title }}</h1>
            <p class="text-light mb-0">
              <i class="fas fa-user-graduate me-2"></i>Completed by: {{ Auth::guard('student')->user()->name }}
            </p>
            <p class="text-light">
              <i class="fas fa-book me-2"></i>Subject: {{ optional($examResult->exam->subject)->name ?? 'General' }}
            </p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <!-- Quick Stats -->
          <div class="stats-grid">
            <div class="stat-card">
              <i class="fas fa-star"></i>
              <div class="stat-value">{{ $examResult->total_score }}</div>
              <div class="stat-label">Score</div>
            </div>
            <div class="stat-card">
              <i class="fas fa-check-circle"></i>
              <div class="stat-value">{{ $correctAnswers ?? $examResult->studentAnswers->where('is_correct', true)->count() }}</div>
              <div class="stat-label">Correct</div>
            </div>
            <div class="stat-card">
              <i class="fas fa-times-circle"></i>
              <div class="stat-value">{{ $wrongAnswers ?? $examResult->studentAnswers->where('is_correct', false)->count() }}</div>
              <div class="stat-label">Wrong</div>
            </div>
            <div class="stat-card">
              <i class="fas fa-clock"></i>
              <div class="stat-value">{{ $examResult->exam->duration }}</div>
              <div class="stat-label">Minutes</div>
            </div>
          </div>

          <!-- Score Breakdown -->
          <div class="score-breakdown">
            <span>Your Score</span>
            <div class="score-bar">
              <div class="score-fill"></div>
            </div>
            <span>Total Score</span>
          </div>
          <div class="text-center mb-4">
            <h3>{{ $examResult->total_score }} / {{ $totalMarks ?? $examResult->exam->questions->sum('mark') }}</h3>
            <p class="text-muted">Points Earned</p>
          </div>

          <!-- Time Information -->
          <div class="time-info">
            <div class="row">
              <div class="col-md-6">
                <p><i class="fas fa-play-circle me-2"></i>Started: {{ \Carbon\Carbon::parse($examResult->started_at)->format('h:i A') }}</p>
              </div>
              <div class="col-md-6">
                <p><i class="fas fa-flag-checkered me-2"></i>Submitted: {{ \Carbon\Carbon::parse($examResult->submitted_at)->format('h:i A') }}</p>
              </div>
            </div>
            @php
                $duration = \Carbon\Carbon::parse($examResult->started_at)->diffInMinutes($examResult->submitted_at);
            @endphp
            <p class="mb-0"><i class="fas fa-hourglass-half me-2"></i>Time Taken: {{ floor($duration) }} mins {{ round(($duration - floor($duration)) * 60) }} secs ({{ floor($examResult->exam->duration - $duration) }} mins {{ round((($examResult->exam->duration - $duration) - floor($examResult->exam->duration - $duration)) * 60) }} secs remaining)</p>
          </div>

          <!-- Results Wizard -->
          <form id="result-form" class="mt-4">
            <div>
              <h3><i class="fas fa-chart-pie me-2"></i>Summary</h3>
              <section>
                <div class="row">
                  <div class="col-md-6">
                    <div class="list-group">
                      <div class="list-group-item">
                        <strong>Exam Status:</strong>
                        <span class="badge bg-success float-end">{{ ucfirst($examResult->status) }}</span>
                      </div>
                      <div class="list-group-item">
                        <strong>Percentage:</strong>
                        <span class="float-end">{{ round($examResult->percentage, 2) }}%</span>
                      </div>
                      <div class="list-group-item">
                        <strong>Total Questions:</strong>
                        <span class="float-end">{{ $examResult->exam->questions->count() }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="list-group">
                      <div class="list-group-item">
                        <strong>Attempted:</strong>
                        <span class="float-end">{{ $examResult->studentAnswers->whereNotNull('selected_option_id')->count() }}</span>
                      </div>
                      <div class="list-group-item">
                        <strong>Accuracy:</strong>
                        <span class="float-end">
                          @php
                              $attempted = $examResult->studentAnswers->whereNotNull('selected_option_id')->count();
                              $correct = $examResult->studentAnswers->where('is_correct', true)->count();
                              $accuracy = $attempted > 0 ? ($correct / $attempted) * 100 : 0;
                          @endphp
                          {{ round($accuracy, 1) }}%
                        </span>
                      </div>
                      <div class="list-group-item">
                        <strong>Performance:</strong>
                        <span class="float-end">
                          @if($examResult->percentage >= 80) Excellent
                          @elseif($examResult->percentage >= 70) Good
                          @elseif($examResult->percentage >= 60) Average
                          @elseif($examResult->percentage >= 50) Below Average
                          @else Needs Improvement
                          @endif
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <h3><i class="fas fa-list-ol me-2"></i>Question Details</h3>
              <section>
                @foreach($examResult->studentAnswers as $index => $answer)
                  <div class="question-container {{ $answer->is_correct ? 'correct' : 'incorrect' }}">
                    <div class="mb-3">
                      <span class="question-number">{{ $index+1 }}</span>
                      <strong>{!! $answer->question->question_text !!}</strong>
                      <span class="answer-status {{ $answer->is_correct ? 'correct' : 'incorrect' }}">
                        {{ $answer->is_correct ? 'Correct' : 'Wrong' }}
                      </span>
                      <span class="float-end badge bg-light text-dark">
                        <i class="fas fa-star me-1"></i>{{ $answer->marks_obtained }} / {{ $answer->question->mark }} points
                      </span>
                    </div>
                    
                    <div class="ms-5">
                      @foreach($answer->question->options as $optIndex => $option)
                        <div class="mb-2">
                          <span class="option-indicator 
                            {{ $answer->selected_option_id == $option->id ? 'selected' : '' }}
                            {{ $option->is_correct ? 'correct' : 'not-selected' }}">
                            {{ chr(65 + $optIndex) }}
                          </span>
                          {{ $option->option_text }}
                          @if($option->is_correct)
                            <span class="badge bg-success ms-2"><i class="fas fa-check"></i> Correct Answer</span>
                          @endif
                        </div>
                      @endforeach
                      
                      <div class="mt-3">
                        <strong>Your Answer:</strong> 
                        {{ optional($answer->selectedOption)->option_text ?? 'No Answer Selected' }}
                      </div>
                    </div>
                  </div>
                @endforeach
              </section>
            </div>
          </form>

          <!-- Action Buttons -->
          <div class="action-buttons">
           <!--  <button onclick="window.print()" class="btn btn-print">
              <i class="fas fa-print"></i> Print Result
            </button>
            <button onclick="downloadResult()" class="btn btn-download">
              <i class="fas fa-download"></i> Download PDF
            </button> -->
            <a href="{{ route('student/logout') }}" class="btn btn-secondary">
              <i class="fas fa-home"></i> Back to Dashboard
            </a>
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
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<script>
// Initialize wizard
var form = $("#result-form");
form.children("div").steps({ 
    headerTag: "h3", 
    bodyTag: "section", 
    transitionEffect: "slideLeft",
    enableFinishButton: false,
    enablePagination: true,
    labels: {
        next: "Next <i class='fas fa-arrow-right ms-1'></i>",
        previous: "<i class='fas fa-arrow-left me-1'></i> Previous"
    }
});

// Animate score bar on page load
$(document).ready(function() {
    $('.score-fill').css('width', '0%');
    setTimeout(function() {
        $('.score-fill').css('width', '{{ $examResult->percentage }}%');
    }, 500);
});

// Download PDF function
function downloadResult() {
    alert('LEAVE THE HALL PLS');
   
}

// Print styling
@media print {
    .page-breadcrumb, .footer, .action-buttons, .wizard > .actions {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .result-header {
        background: #667eea !important;
        -webkit-print-color-adjust: exact;
    }
}
</script>
</body>
</html>