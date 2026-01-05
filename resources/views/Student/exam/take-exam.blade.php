@include('components.header') 
<link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>
    /* Force fullscreen styling - ALWAYS applied */
    body {
        overflow: hidden !important;
    }
    
    #main-wrapper {
        margin-left: 0 !important;
        padding-top: 0 !important;
    }
    
    .page-wrapper {
        padding-top: 0;
        padding-bottom: 0;
    }
    
    .page-breadcrumb {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        border-bottom: 1px solid #dee2e6;
        padding: 15px;
        margin: 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .container-fluid {
        padding-top: 80px; /* Space for fixed header */
        height: calc(100vh - 80px);
        margin: 0;
        max-width: 100%;
        padding-left: 0;
        padding-right: 0;
    }
    
    .card {
        height: 100%;
        border: none;
        border-radius: 0;
        margin: 0;
    }
    
    .card-body {
        height: 100%;
        overflow-y: auto;
        padding: 20px;
    }
    
    .footer {
        display: none;
    }
    
    /* Hide sidebar and regular nav */
    .left-sidebar, .navbar-header {
        display: none !important;
    }
    
    /* Timer styling */
    #timer-container {
        position: fixed;
        top: 15px;
        right: 20px;
        z-index: 1001;
    }
    
    #timer {
        font-size: 1.2rem;
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 20px;
        min-width: 150px;
        text-align: center;
    }
    
    .timer-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
        animation: pulse 1s infinite;
    }
    
    .timer-danger {
        background-color: #dc3545 !important;
        color: white !important;
        animation: pulse-danger 0.5s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    @keyframes pulse-danger {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    /* Exam question styling */
    .wizard > .steps > ul > li {
        width: auto !important;
        margin: 0 5px !important;
    }
    
    .wizard > .content {
        min-height: 300px;
        margin-top: 20px;
    }
    
    .question-text {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
        border-left: 4px solid #007bff;
    }
    
    .option-container {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .option-container:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
    }
    
    .option-container.selected {
        background-color: #e7f3ff;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }
    
    .question-image-container {
        margin: 15px 0;
        text-align: center;
    }
    
    .question-image {
        max-width: 100%;
        max-height: 400px;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }
    
    /* Fullscreen warning overlay */
    #fullscreen-warning {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.95);
        color: white;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 20px;
    }
    
    .warning-content {
        max-width: 600px;
        background: #343a40;
        padding: 40px;
        border-radius: 10px;
        border: 2px solid #dc3545;
    }
    
    .warning-content h2 {
        color: #ff6b6b;
        margin-bottom: 20px;
    }
    
    .warning-content p {
        margin-bottom: 15px;
        font-size: 1.1rem;
    }
</style>

<body id="exam-body">
<!-- Fullscreen Warning (shown if not in fullscreen) -->
<div id="fullscreen-warning" style="display: none;">
    <div class="warning-content">
        <h2><i class="fas fa-exclamation-triangle me-2"></i> FULLSCREEN REQUIRED</h2>
        <p>You must be in fullscreen mode to take this exam.</p>
        <p>The exam will automatically switch to fullscreen mode.</p>
        <p class="mt-4"><i class="fas fa-lock me-2"></i>You cannot exit fullscreen mode during the exam.</p>
        <p class="text-muted mt-3">If fullscreen doesn't activate automatically, please allow it in your browser settings.</p>
    </div>
</div>

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
          <h4 class="page-title">Take Exam: {{ $exam->title }}</h4>
          <div id="timer-container">
            <div id="timer" class="badge bg-primary fs-6"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="card">
        <div class="card-body wizard-content">
          <h1 class="card-title">{{ $exam->title }}</h1>
          <h6 class="card-subtitle mb-4">Duration: {{ $exam->duration }} minutes | Total Questions: {{ $questions->count() }}</h6>

    <form id="exam-form" method="POST" action="{{ route('student.exam.submit', $exam->id) }}">
      @csrf
      <div>
        @foreach($questions as $index => $question)
          <h3>Question {{ $index + 1 }} of {{ $questions->count() }}</h3>
          <section>
            <div class="question-text">{!! $question->question_text !!}</div>
            
            {{-- Display question image if it exists and is not empty --}}
            @if($question->question_image && $question->question_image != '')
              <div class="question-image-container">
                <img src="{{ $question->question_image }}" 
                     alt="Question {{ $index + 1 }} image" 
                     class="question-image">
              </div>
            @endif
            
            <div class="options-container">
              @foreach($question->options as $optionIndex => $option)
              <div class="option-container" onclick="selectOption(this, {{ $question->id }}, {{ $option->id }})">
                <div class="form-check">
                  <input class="form-check-input" type="radio" 
                         name="answers[{{ $question->id }}]" 
                         id="question_{{ $question->id }}_option_{{ $option->id }}"
                         value="{{ $option->id }}" 
                         {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'checked' : '' }}
                         required>
                  <label class="form-check-label" for="question_{{ $question->id }}_option_{{ $option->id }}">
                    <strong>{{ chr(65 + $optionIndex) }}.</strong> {{ $option->option_text }}
                  </label>
                </div>
              </div>
              @endforeach
            </div>
          </section>
        @endforeach
      </div>
    </form>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Initialize variables
var remainingMinutes = {{ $remainingMinutes }};
var totalSeconds = remainingMinutes * 60;
var timerInterval;
var isFullscreenMode = false;
var fullscreenAttempted = false;

// Function to enforce fullscreen
function enforceFullscreen() {
    var elem = document.documentElement;
    
    // Try to enter fullscreen
    if (elem.requestFullscreen) {
        elem.requestFullscreen().catch(err => {
            showFullscreenWarning();
        });
    } else if (elem.webkitRequestFullscreen) {
        elem.webkitRequestFullscreen().catch(err => {
            showFullscreenWarning();
        });
    } else if (elem.msRequestFullscreen) {
        elem.msRequestFullscreen().catch(err => {
            showFullscreenWarning();
        });
    } else {
        showFullscreenWarning();
    }
}

// Show warning if fullscreen fails
function showFullscreenWarning() {
    document.getElementById('fullscreen-warning').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Try again every 2 seconds
    setInterval(() => {
        if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            enforceFullscreen();
        }
    }, 2000);
}

// Check fullscreen status
function checkFullscreen() {
    isFullscreenMode = !!(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
    
    if (!isFullscreenMode) {
        enforceFullscreen();
    }
}

// Prevent exiting fullscreen
document.addEventListener('fullscreenchange', handleFullscreenChange);
document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
document.addEventListener('msfullscreenchange', handleFullscreenChange);

function handleFullscreenChange() {
    isFullscreenMode = !!(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
    
    if (!isFullscreenMode) {
        // Immediately re-enter fullscreen if user tries to exit
        setTimeout(enforceFullscreen, 100);
    } else {
        // Hide warning if shown
        document.getElementById('fullscreen-warning').style.display = 'none';
    }
}

// Prevent right-click, F12, Ctrl+Shift+I, etc.
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    Swal.fire({
        icon: 'warning',
        title: 'Action Disabled',
        text: 'Right-click is disabled during the exam.',
        confirmButtonColor: '#007bff'
    });
});

document.addEventListener('keydown', function(e) {
    // Prevent F12
    if (e.key === 'F12') {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Action Disabled',
            text: 'Developer tools are disabled during the exam.',
            confirmButtonColor: '#dc3545'
        });
    }
    
    // Prevent Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C, Ctrl+U
    if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key)) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Action Disabled',
            text: 'Developer tools are disabled during the exam.',
            confirmButtonColor: '#dc3545'
        });
    }
    
    // Prevent Ctrl+U (view source)
    if (e.ctrlKey && e.key === 'u') {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Action Disabled',
            text: 'View source is disabled during the exam.',
            confirmButtonColor: '#dc3545'
        });
    }
    
    // Prevent print screen (not fully reliable but helps)
    if (e.key === 'PrintScreen') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Action Disabled',
            text: 'Screenshots are disabled during the exam.',
            confirmButtonColor: '#ffc107'
        });
    }
});

// Start timer
function startTimer() {
    clearInterval(timerInterval);
    
    timerInterval = setInterval(function() {
        totalSeconds--;
        
        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            submitExam();
            return;
        }
        
        updateTimerDisplay();
    }, 1000);
    
    updateTimerDisplay();
}

// Function to update timer display
function updateTimerDisplay() {
    var minutes = Math.floor(totalSeconds / 60);
    var seconds = totalSeconds % 60;
    
    var timerElement = document.getElementById('timer');
    var timerText = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
    
    // Update timer text
    timerElement.innerHTML = '<i class="fas fa-clock me-2"></i>' + timerText;
    
    // Change color based on time remaining
    if (totalSeconds <= 300) { // 5 minutes or less
        timerElement.className = 'badge timer-danger fs-6';
    } else if (totalSeconds <= 600) { // 10 minutes or less
        timerElement.className = 'badge timer-warning fs-6';
    } else {
        timerElement.className = 'badge bg-primary fs-6';
    }
}

// Function to submit exam
function submitExam() {
    Swal.fire({
        icon: 'warning',
        title: 'Time\'s Up!',
        text: 'Your exam will be submitted now.',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    }).then(() => {
        document.getElementById('exam-form').submit();
    });
}

// Function to select an option
function selectOption(container, questionId, optionId) {
    var radioButton = container.querySelector('input[type="radio"]');
    radioButton.checked = true;
    
    // Remove selected class from all options for this question
    var allOptions = document.querySelectorAll('.option-container');
    allOptions.forEach(function(opt) {
        if (opt.querySelector('input[name="answers[' + questionId + ']"]')) {
            opt.classList.remove('selected');
        }
    });
    
    // Add selected class to clicked option
    container.classList.add('selected');
    $(radioButton).trigger('change');
}

// Form wizard initialization
var form = $("#exam-form");
form.validate({
    errorPlacement: function(error, element) { 
        error.addClass('alert alert-danger mt-2');
        element.before(error); 
    },
    rules: {
        'answers[{{ $questions->first()->id ?? 0 }}]': { required: true }
    }
});

form.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    enableFinishButton: true,
    enablePagination: true,
    labels: {
        finish: "Submit Exam",
        next: "Next Question",
        previous: "Previous Question"
    },
    onStepChanging: function(event, currentIndex, newIndex) {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function() { 
        return new Promise((resolve) => {
            Swal.fire({
                title: 'Submit Exam?',
                text: "Are you sure you want to submit the exam? You cannot return to the exam after submission.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Submit!',
                cancelButtonText: 'No, Review'
            }).then((result) => {
                if (result.isConfirmed && form.valid()) {
                    resolve(true);
                } else {
                    resolve(false);
                }
            });
        });
    },
    onFinished: function() { 
        form.submit(); 
    }
});

// Prevent leaving page
window.addEventListener('beforeunload', function (e) {
    if (totalSeconds > 0) {
        e.preventDefault();
        e.returnValue = 'You have an exam in progress. Are you sure you want to leave?';
        return e.returnValue;
    }
});

// Initialize everything when page loads
$(document).ready(function() {
    // Hide sidebar and nav elements
    $('.left-sidebar, .navbar-header').hide();
    
    // Show welcome message and enter fullscreen
    Swal.fire({
        title: 'Welcome to Your Exam!',
        html: '<p>Your exam will start in fullscreen mode.</p><p class="text-muted mb-0">Duration: {{ $exam->duration }} minutes</p>',
        icon: 'info',
        confirmButtonText: 'Start Exam',
        confirmButtonColor: '#007bff',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Enter fullscreen immediately after user clicks start
            enforceFullscreen();
            
            // Start the timer
            startTimer();
        }
    });
    
    // Check fullscreen every second
    setInterval(checkFullscreen, 1000);
    
    // Apply selected class to already selected options
    $('input[type="radio"]:checked').each(function() {
        $(this).closest('.option-container').addClass('selected');
    });
});
</script>
</body>
</html>