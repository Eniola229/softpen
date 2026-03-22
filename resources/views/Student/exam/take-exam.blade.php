@include('components.header') 
<link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>
    body { overflow: hidden !important; }
    #main-wrapper { margin-left: 0 !important; padding-top: 0 !important; }
    .page-wrapper { padding-top: 0; padding-bottom: 0; }
    .page-breadcrumb {
        position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        background: white; border-bottom: 1px solid #dee2e6;
        padding: 15px; margin: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .container-fluid {
        padding-top: 80px; height: calc(100vh - 80px);
        margin: 0; max-width: 100%; padding-left: 0; padding-right: 0;
    }
    .card { height: 100%; border: none; border-radius: 0; margin: 0; }
    .card-body { height: 100%; overflow-y: auto; padding: 20px; }
    .footer { display: none; }
    .left-sidebar, .navbar-header { display: none !important; }

    /* Timer */
    #timer-container { position: fixed; top: 15px; right: 170px; z-index: 1001; }
    #timer { font-size: 1.2rem; font-weight: bold; padding: 8px 15px; border-radius: 20px; min-width: 150px; text-align: center; }
    .timer-warning { background-color: #ffc107 !important; color: #212529 !important; animation: pulse 1s infinite; }
    .timer-danger  { background-color: #dc3545 !important; color: white !important; animation: pulse-danger 0.5s infinite; }
    @keyframes pulse        { 0%,100% { opacity:1; } 50% { opacity:0.7; } }
    @keyframes pulse-danger { 0%,100% { opacity:1; } 50% { opacity:0.5; } }

    /* Calculator button */
    #calc-toggle-btn {
        position: fixed; top: 12px; right: 20px; z-index: 1002;
        background: #17a2b8; color: white; border: none; border-radius: 20px;
        padding: 8px 16px; font-size: 0.9rem; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; gap: 6px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2); transition: all 0.2s;
    }
    #calc-toggle-btn:hover { background: #138496; transform: translateY(-1px); }

    /* Calculator modal */
    #calc-modal { display: none; position: fixed; top: 60px; right: 20px; z-index: 2000; background: #1e1e2e; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.5); padding: 16px; width: 320px; }
    #calc-modal.show { display: block; animation: slideInCalc 0.2s ease; }
    @keyframes slideInCalc { from { opacity:0; transform: translateY(-10px) scale(0.97); } to { opacity:1; transform: translateY(0) scale(1); } }
    .calc-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .calc-header h6 { color: #cdd6f4; margin: 0; font-size: 0.85rem; font-weight: 600; }
    .calc-close-btn { background: #45475a; border: none; color: #cdd6f4; border-radius: 6px; width: 26px; height: 26px; cursor: pointer; display:flex; align-items:center; justify-content:center; font-size: 1rem; }
    .calc-close-btn:hover { background: #f38ba8; color: white; }
    .calc-display { background: #11111b; border-radius: 10px; padding: 12px 14px; margin-bottom: 10px; text-align: right; }
    .calc-expression { color: #6c7086; font-size: 0.78rem; min-height: 18px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .calc-screen { color: #cdd6f4; font-size: 1.8rem; font-weight: 300; min-height: 44px; word-break: break-all; line-height: 1.2; max-height: 80px; overflow-y: auto; }
    .calc-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 5px; }
    .calc-btn { border: none; border-radius: 8px; padding: 10px 4px; font-size: 0.82rem; font-weight: 500; cursor: pointer; transition: all 0.1s; color: #cdd6f4; display: flex; align-items:center; justify-content:center; min-height: 40px; }
    .calc-btn:active { transform: scale(0.94); }
    .calc-btn.num { background: #313244; } .calc-btn.num:hover { background: #45475a; }
    .calc-btn.op  { background: #45475a; color: #89b4fa; } .calc-btn.op:hover  { background: #585b70; }
    .calc-btn.fn  { background: #363749; color: #a6e3a1; font-size: 0.74rem; } .calc-btn.fn:hover  { background: #45475a; }
    .calc-btn.eq  { background: #89b4fa; color: #1e1e2e; font-weight: 700; } .calc-btn.eq:hover  { background: #74c7ec; }
    .calc-btn.clr { background: #f38ba8; color: #1e1e2e; font-weight: 700; } .calc-btn.clr:hover { background: #eba0ac; }
    .calc-btn.del { background: #fab387; color: #1e1e2e; } .calc-btn.del:hover { background: #f9e2af; }

    /* Question styles */
    .question-text { font-size: 1.1rem; font-weight: 500; margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; border-left: 4px solid #007bff; }
    .option-container { margin-bottom: 10px; padding: 10px; border: 1px solid #dee2e6; border-radius: 5px; transition: all 0.2s; cursor: pointer; }
    .option-container:hover { background-color: #f8f9fa; border-color: #007bff; }
    .option-container.selected { background-color: #e7f3ff; border-color: #007bff; box-shadow: 0 0 0 2px rgba(0,123,255,0.25); }
    .question-image-container { margin: 15px 0; text-align: center; }
    .question-image { max-width:100%; max-height:400px; height:auto; border-radius:8px; }

    /* ===== Fullscreen overlay ===== */
    #fullscreen-overlay {
        display: none;
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.97); color: white;
        z-index: 99999; align-items: center; justify-content: center;
        text-align: center; padding: 20px;
    }
    #fullscreen-overlay.active { display: flex; }
    .fs-box {
        max-width: 500px; background: #1a1a2e;
        padding: 50px 40px; border-radius: 16px; border: 2px solid #0d6efd;
    }
    .fs-box .fs-icon { font-size: 3.5rem; color: #0d6efd; margin-bottom: 20px; }
    .fs-box h2 { color: #ffffff; font-size: 1.6rem; margin-bottom: 12px; }
    .fs-box p  { color: #adb5bd; font-size: 0.95rem; margin-bottom: 8px; }
    .fs-start-btn {
        margin-top: 28px; padding: 14px 40px;
        background: #0d6efd; color: white; border: none;
        border-radius: 30px; font-size: 1.1rem; font-weight: 700;
        cursor: pointer; transition: all 0.25s;
        box-shadow: 0 6px 24px rgba(13,110,253,0.45);
        display: inline-flex; align-items: center; gap: 10px;
    }
    .fs-start-btn:hover { background: #0b5ed7; transform: translateY(-2px); box-shadow: 0 10px 30px rgba(13,110,253,0.5); }

    /* Returned-from-fullscreen warning */
    #fs-return-overlay {
        display: none;
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.97); color: white;
        z-index: 99999; align-items: center; justify-content: center;
        text-align: center; padding: 20px;
    }
    #fs-return-overlay.active { display: flex; }
    .fs-return-box {
        max-width: 500px; background: #1a1a2e;
        padding: 50px 40px; border-radius: 16px; border: 2px solid #dc3545;
    }
    .fs-return-box h2 { color: #ff6b6b; font-size: 1.6rem; margin-bottom: 12px; }
    .fs-return-box p  { color: #adb5bd; font-size: 0.95rem; margin-bottom: 8px; }
    .fs-return-btn {
        margin-top: 28px; padding: 14px 40px;
        background: #dc3545; color: white; border: none;
        border-radius: 30px; font-size: 1.1rem; font-weight: 700;
        cursor: pointer; transition: all 0.25s;
        display: inline-flex; align-items: center; gap: 10px;
    }
    .fs-return-btn:hover { background: #bb2d3b; transform: translateY(-2px); }
</style>

<body id="exam-body">

{{-- Initial fullscreen prompt --}}
<div id="fullscreen-overlay">
    <div class="fs-box">
        <div class="fs-icon"><i class="fas fa-expand-arrows-alt"></i></div>
        <h2>Fullscreen Required</h2>
        <p>This exam must be taken in fullscreen mode.</p>
        <p>Your timer will start as soon as you enter fullscreen.</p>
        <button class="fs-start-btn" onclick="enterFullscreen()">
            <i class="fas fa-play-circle"></i> Enter Fullscreen & Begin
        </button>
    </div>
</div>

{{-- Shown if student exits fullscreen mid-exam --}}
<div id="fs-return-overlay">
    <div class="fs-return-box">
        <h2><i class="fas fa-exclamation-triangle me-2"></i> You Left Fullscreen!</h2>
        <p>You must return to fullscreen to continue your exam.</p>
        <p style="color:#ff6b6b; font-size:0.85rem;">Your timer is still running.</p>
        <button class="fs-return-btn" onclick="reEnterFullscreen()">
            <i class="fas fa-expand-arrows-alt"></i> Return to Fullscreen
        </button>
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

    <button id="calc-toggle-btn" onclick="toggleCalculator()" title="Open Scientific Calculator">
      <i class="fas fa-calculator"></i> Calculator
    </button>

    <div id="calc-modal">
      <div class="calc-header">
        <h6>🔬 Scientific Calculator</h6>
        <button class="calc-close-btn" onclick="toggleCalculator()">✕</button>
      </div>
      <div class="calc-display">
        <div class="calc-expression" id="calc-expression"></div>
        <div class="calc-screen" id="calc-screen">0</div>
      </div>
      <div class="calc-grid">
        <button class="calc-btn fn" onclick="calcFn('sin(')">sin</button>
        <button class="calc-btn fn" onclick="calcFn('cos(')">cos</button>
        <button class="calc-btn fn" onclick="calcFn('tan(')">tan</button>
        <button class="calc-btn fn" onclick="calcFn('log(')">log</button>
        <button class="calc-btn fn" onclick="calcFn('ln(')">ln</button>
        <button class="calc-btn fn" onclick="calcFn('sqrt(')">√</button>
        <button class="calc-btn fn" onclick="calcFn('**2')">x²</button>
        <button class="calc-btn fn" onclick="calcFn('**')">xʸ</button>
        <button class="calc-btn fn" onclick="calcFn('(')"> ( </button>
        <button class="calc-btn fn" onclick="calcFn(')')"> ) </button>
        <button class="calc-btn fn" onclick="calcFn('Math.PI')">π</button>
        <button class="calc-btn fn" onclick="calcFn('Math.E')">e</button>
        <button class="calc-btn fn" onclick="calcFn('1/')">1/x</button>
        <button class="calc-btn fn" onclick="calcFn('%')">%</button>
        <button class="calc-btn del" onclick="calcDel()">⌫</button>
        <button class="calc-btn clr" onclick="calcClear()">AC</button>
        <button class="calc-btn num" onclick="calcInput('7')">7</button>
        <button class="calc-btn num" onclick="calcInput('8')">8</button>
        <button class="calc-btn num" onclick="calcInput('9')">9</button>
        <button class="calc-btn op"  onclick="calcInput('/')">÷</button>
        <button class="calc-btn fn"  onclick="calcFn('Math.abs(')">|x|</button>
        <button class="calc-btn num" onclick="calcInput('4')">4</button>
        <button class="calc-btn num" onclick="calcInput('5')">5</button>
        <button class="calc-btn num" onclick="calcInput('6')">6</button>
        <button class="calc-btn op"  onclick="calcInput('*')">×</button>
        <button class="calc-btn fn"  onclick="calcFn('Math.floor(')">⌊x⌋</button>
        <button class="calc-btn num" onclick="calcInput('1')">1</button>
        <button class="calc-btn num" onclick="calcInput('2')">2</button>
        <button class="calc-btn num" onclick="calcInput('3')">3</button>
        <button class="calc-btn op"  onclick="calcInput('-')">−</button>
        <button class="calc-btn fn"  onclick="calcFn('Math.ceil(')">⌈x⌉</button>
        <button class="calc-btn num" onclick="calcInput('0')">0</button>
        <button class="calc-btn num" onclick="calcInput('.')">.</button>
        <button class="calc-btn eq"  onclick="calcEquals()">=</button>
        <button class="calc-btn op"  onclick="calcInput('+')">+</button>
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
                  @if($question->question_image && $question->question_image != '')
                    <div class="question-image-container">
                      <img src="{{ $question->question_image }}" alt="Question image" class="question-image">
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

    <footer class="footer text-center">All Rights Reserved by SoftPenTech | Developed by SoftpenTech</footer>
  </div>
</div>

<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ===== State =====
var remainingMinutes = {{ $remainingMinutes }};
var totalSeconds     = Math.floor(remainingMinutes * 60);
var timerInterval    = null;
var examStarted      = false;

// ===== Fullscreen helpers =====
function isInFullscreen() {
    return !!(document.fullscreenElement || document.webkitFullscreenElement
            || document.mozFullScreenElement || document.msFullscreenElement);
}

function requestFS() {
    var el = document.documentElement;
    return (el.requestFullscreen || el.webkitRequestFullscreen
          || el.mozRequestFullScreen || el.msRequestFullscreen || function(){}).call(el);
}

function enterFullscreen() {
    var el = document.documentElement;
    var fsMethod = el.requestFullscreen || el.webkitRequestFullscreen 
                 || el.mozRequestFullScreen || el.msRequestFullscreen;
    
    if (!fsMethod) {
        // Browser doesn't support fullscreen — just start the exam anyway
        onFullscreenGranted();
        return;
    }

    fsMethod.call(el).then(function() {
        onFullscreenGranted();
    }).catch(function(err) {
        console.warn('Fullscreen failed:', err);
        // Still start the exam even if fullscreen is denied
        onFullscreenGranted();
    });
}

function reEnterFullscreen() {
    var el = document.documentElement;
    var fsMethod = el.requestFullscreen || el.webkitRequestFullscreen 
                 || el.mozRequestFullScreen || el.msRequestFullscreen;

    if (!fsMethod) {
        document.getElementById('fs-return-overlay').classList.remove('active');
        return;
    }

    fsMethod.call(el).then(function() {
        document.getElementById('fs-return-overlay').classList.remove('active');
    }).catch(function() {
        document.getElementById('fs-return-overlay').classList.remove('active');
    });
}

function onFullscreenGranted() {
    // Hide the initial prompt
    document.getElementById('fullscreen-overlay').classList.remove('active');
    // Start the exam timer
    if (!examStarted) {
        examStarted = true;
        startTimer();
    }
}

// Listen for fullscreen exit
['fullscreenchange','webkitfullscreenchange','mozfullscreenchange','MSFullscreenChange']
    .forEach(function(ev) { document.addEventListener(ev, onFsChange); });

function onFsChange() {
    if (!examStarted) return; // don't interfere before exam starts
    if (!isInFullscreen()) {
        // Student pressed Escape or browser button — show return overlay
        document.getElementById('fs-return-overlay').classList.add('active');
    } else {
        document.getElementById('fs-return-overlay').classList.remove('active');
    }
}

// ===== Timer =====
function startTimer() {
    updateTimerDisplay();
    timerInterval = setInterval(function() {
        totalSeconds--;
        if (totalSeconds <= 0) { clearInterval(timerInterval); autoSubmit(); return; }
        updateTimerDisplay();
    }, 1000);
}

function updateTimerDisplay() {
    var m  = Math.floor(totalSeconds / 60);
    var s  = totalSeconds % 60;
    var el = document.getElementById('timer');
    el.innerHTML = '<i class="fas fa-clock me-1"></i>' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    if      (totalSeconds <= 300) el.className = 'badge timer-danger fs-6';
    else if (totalSeconds <= 600) el.className = 'badge timer-warning fs-6';
    else                          el.className = 'badge bg-primary fs-6';
}

function autoSubmit() {
    Swal.fire({ icon:'warning', title:"Time's Up!", text:'Submitting your exam now.',
        showConfirmButton:false, timer:2000, timerProgressBar:true
    }).then(function() { document.getElementById('exam-form').submit(); });
}

// ===== Security =====
document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
document.addEventListener('keydown', function(e) {
    if (e.key === 'F12') { e.preventDefault(); return; }
    if (e.ctrlKey && e.shiftKey && ['I','J','C'].includes(e.key)) { e.preventDefault(); return; }
    if (e.ctrlKey && e.key === 'u') { e.preventDefault(); return; }
});
window.addEventListener('beforeunload', function(e) {
    if (examStarted && totalSeconds > 0) { e.preventDefault(); e.returnValue = ''; }
});

// ===== Option selection =====
function selectOption(container, questionId, optionId) {
    var radio = container.querySelector('input[type="radio"]');
    radio.checked = true;
    document.querySelectorAll('.option-container').forEach(function(opt) {
        if (opt.querySelector('input[name="answers[' + questionId + ']"]')) opt.classList.remove('selected');
    });
    container.classList.add('selected');
    $(radio).trigger('change');
}

// ===== Wizard =====
var form = $("#exam-form");
form.validate({
    errorPlacement: function(error, element) { error.addClass('alert alert-danger mt-2'); element.before(error); }
});
form.children("div").steps({
    headerTag: "h3", bodyTag: "section", transitionEffect: "slideLeft",
    enableFinishButton: true, enablePagination: true,
    labels: { finish: "Submit Exam", next: "Next Question", previous: "Previous Question" },
    onStepChanging: function(event, currentIndex, newIndex) {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function() {
        return new Promise(function(resolve) {
            Swal.fire({
                title: 'Submit Exam?', text: "You cannot return after submission.",
                icon: 'question', showCancelButton: true,
                confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Submit!', cancelButtonText: 'No, Review'
            }).then(function(result) { resolve(result.isConfirmed && form.valid()); });
        });
    },
    onFinished: function() { form.submit(); }
});

// ===== Init =====
$(document).ready(function() {
    $('.left-sidebar, .navbar-header').hide();
    // Show the initial fullscreen prompt
    document.getElementById('fullscreen-overlay').classList.add('active');
    // Mark already-answered options
    $('input[type="radio"]:checked').each(function() {
        $(this).closest('.option-container').addClass('selected');
    });
});

// ===== Calculator =====
var calcExpression = '', calcBuffer = '0', calcJustEvaled = false;

function toggleCalculator() { document.getElementById('calc-modal').classList.toggle('show'); }
function updateCalcDisplay() {
    document.getElementById('calc-screen').textContent     = calcBuffer;
    document.getElementById('calc-expression').textContent = calcExpression;
}
function calcInput(val) {
    if (calcJustEvaled && /[\d\.]/.test(val)) { calcBuffer = ''; calcExpression = ''; }
    calcJustEvaled = false;
    calcBuffer = (calcBuffer === '0' && /\d/.test(val) && val !== '.') ? val : calcBuffer + val;
    updateCalcDisplay();
}
function calcFn(fn) {
    calcJustEvaled = false;
    if      (fn === '**2') calcBuffer += '**2';
    else if (fn === '1/')  calcBuffer  = '1/(' + calcBuffer + ')';
    else                   calcBuffer += fn;
    updateCalcDisplay();
}
function calcClear()  { calcBuffer = '0'; calcExpression = ''; calcJustEvaled = false; updateCalcDisplay(); }
function calcDel()    { calcBuffer = calcBuffer.length > 1 ? calcBuffer.slice(0,-1) : '0'; updateCalcDisplay(); }
function calcEquals() {
    try {
        var expr = calcBuffer
            .replace(/÷/g,'/')   .replace(/×/g,'*')     .replace(/−/g,'-')
            .replace(/sqrt\(/g,'Math.sqrt(')  .replace(/log\(/g,'Math.log10(')
            .replace(/ln\(/g,'Math.log(')     .replace(/sin\(/g,'Math.sin(')
            .replace(/cos\(/g,'Math.cos(')    .replace(/tan\(/g,'Math.tan(');
        calcExpression = calcBuffer + ' =';
        var result = eval(expr);
        if (typeof result === 'number') result = parseFloat(result.toPrecision(12));
        calcBuffer = String(result); calcJustEvaled = true;
    } catch(e) { calcBuffer = 'Error'; calcJustEvaled = true; }
    updateCalcDisplay();
}
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('calc-modal').classList.contains('show')) return;
    if      (/^[0-9\.]$/.test(e.key))            calcInput(e.key);
    else if (e.key === '+')                       calcInput('+');
    else if (e.key === '-')                       calcInput('-');
    else if (e.key === '*')                       calcInput('*');
    else if (e.key === '/')                     { e.preventDefault(); calcInput('/'); }
    else if (e.key === 'Enter'||e.key === '=')    calcEquals();
    else if (e.key === 'Backspace')               calcDel();
    else if (e.key === 'Escape')                  toggleCalculator();
    else if (e.key === 'c' || e.key === 'C')      calcClear();
});
</script>
</body>
</html>