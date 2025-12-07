@include('components.header')
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<style>
    .timer-box {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 1000;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        min-width: 200px;
    }
    .timer-display {
        font-size: 32px;
        font-weight: bold;
        color: #5cb85c;
        text-align: center;
    }
    .timer-display.warning {
        color: #f0ad4e;
    }
    .timer-display.danger {
        color: #d9534f;
        animation: pulse 1s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .question-card {
        margin-bottom: 30px;
    }
    .option-item {
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .option-item:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
    }
    .option-item.selected {
        background-color: #e3f2fd;
        border-color: #2196F3;
    }
    .option-item input[type="radio"] {
        margin-right: 10px;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    .question-image {
        max-width: 100%;
        height: auto;
        margin: 15px 0;
        border-radius: 8px;
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
        @include('components.student-nav')

        <!-- Timer Box -->
        <div class="timer-box">
            <div class="text-center mb-2">
                <i class="fas fa-clock"></i> Time Remaining
            </div>
            <div id="timer" class="timer-display">{{ sprintf('%02d:%02d', floor($remainingMinutes), ($remainingMinutes - floor($remainingMinutes)) * 60) }}</div>
        </div>

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12">
                        <h4 class="page-title">{{ $exam->title }}</h4>
                        <p class="text-muted">{{ $exam->subject }} | Duration: {{ $exam->duration }} minutes</p>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <form id="exam-form" action="{{ route('student.exam.submit', $exam->id) }}" method="POST">
                    @csrf

                    @if($exam->show_one_question_at_time)
                        <!-- Show one question at a time -->
                        @foreach($questions as $index => $question)
                        <div class="question-slide" data-question-index="{{ $index }}" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                            <div class="card question-card">
                                <div class="card-body">
                                    <h4 class="mb-3">Question {{ $index + 1 }} of {{ $questions->count() }}</h4>
                                    <h5 class="mb-3">{{ $question->question_text }} <span class="badge bg-primary">{{ $question->mark }} pts</span></h5>
                                    
                                    @if($question->question_image)
                                        <img src="{{ $question->question_image }}" alt="Question Image" class="question-image">
                                    @endif

                                    <div class="options-container mt-4">
                                        @foreach($question->options as $option)
                                        <label class="option-item {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'selected' : '' }}">
                                            <input type="radio" 
                                                   name="question_{{ $question->id }}" 
                                                   value="{{ $option->id }}"
                                                   {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'checked' : '' }}
                                                   onchange="selectOption(this)">
                                            <span>{{ chr(65 + $loop->index) }}. {{ $option->option_text }}</span>
                                            @if($option->option_image)
                                                <br><img src="{{ $option->option_image }}" alt="Option Image" style="max-width: 200px; margin-top: 10px;">
                                            @endif
                                        </label>
                                        @endforeach
                                    </div>

                                    <div class="mt-4 d-flex justify-content-between">
                                        @if($index > 0)
                                            <button type="button" class="btn btn-secondary" onclick="previousQuestion({{ $index }})">
                                                <i class="fas fa-arrow-left"></i> Previous
                                            </button>
                                        @else
                                            <div></div>
                                        @endif

                                        @if($index < $questions->count() - 1)
                                            <button type="button" class="btn btn-primary" onclick="nextQuestion({{ $index }})">
                                                Next <i class="fas fa-arrow-right"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-success" onclick="confirmSubmit()">
                                                <i class="fas fa-check"></i> Submit Exam
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Show all questions at once -->
                        @foreach($questions as $index => $question)
                        <div class="card question-card">
                            <div class="card-body">
                                <h5 class="mb-3">Question {{ $index + 1 }}. {{ $question->question_text }} <span class="badge bg-primary">{{ $question->mark }} pts</span></h5>
                                
                                @if($question->question_image)
                                    <img src="{{ $question->question_image }}" alt="Question Image" class="question-image">
                                @endif

                                <div class="options-container mt-3">
                                    @foreach($question->options as $option)
                                    <label class="option-item {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'selected' : '' }}">
                                        <input type="radio" 
                                               name="question_{{ $question->id }}" 
                                               value="{{ $option->id }}"
                                               {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'checked' : '' }}
                                               onchange="selectOption(this)">
                                        <span>{{ chr(65 + $loop->index) }}. {{ $option->option_text }}</span>
                                        @if($option->option_image)
                                            <br><img src="{{ $option->option_image }}" alt="Option Image" style="max-width: 200px; margin-top: 10px;">
                                        @endif
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="card">
                            <div class="card-body text-center">
                                <button type="button" class="btn btn-success btn-lg" onclick="confirmSubmit()">
                                    <i class="fas fa-check"></i> Submit Exam
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Timer functionality
        let remainingSeconds = {{ $remainingMinutes * 60 }};
        const timerElement = document.getElementById('timer');
        
        function updateTimer() {
            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                autoSubmit();
                return;
            }
            
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            
            timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            // Change color based on time remaining
            if (remainingSeconds <= 60) {
                timerElement.className = 'timer-display danger';
            } else if (remainingSeconds <= 300) {
                timerElement.className = 'timer-display warning';
            }
            
            remainingSeconds--;
        }
        
        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
        
        function autoSubmit() {
            Swal.fire({
                title: 'Time Up!',
                text: 'Your exam time has expired. Submitting your answers...',
                icon: 'warning',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                document.getElementById('exam-form').submit();
            });
        }
        
        // Question navigation (for one-at-a-time mode)
        function nextQuestion(currentIndex) {
            document.querySelectorAll('.question-slide')[currentIndex].style.display = 'none';
            document.querySelectorAll('.question-slide')[currentIndex + 1].style.display = 'block';
            window.scrollTo(0, 0);
        }
        
        function previousQuestion(currentIndex) {
            document.querySelectorAll('.question-slide')[currentIndex].style.display = 'none';
            document.querySelectorAll('.question-slide')[currentIndex - 1].style.display = 'block';
            window.scrollTo(0, 0);
        }
        
        // Option selection
        function selectOption(radio) {
            // Remove selected class from all options in this question
            const container = radio.closest('.options-container');
            container.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selected class to chosen option
            radio.closest('.option-item').classList.add('selected');
            
            // Auto-save answer
            saveAnswer(radio.name.replace('question_', ''), radio.value);
        }
        
        // Auto-save answer
        function saveAnswer(questionId, optionId) {
            fetch('{{ route("student.exam.save-answer", $exam->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    option_id: optionId
                })
            });
        }
        
        // Confirm submit
        function confirmSubmit() {
            Swal.fire({
                title: 'Submit Exam?',
                text: 'Are you sure you want to submit your answers? This action cannot be undone.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('exam-form').submit();
                }
            });
        }
        
        // Prevent accidental page close
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });
        
        // Disable right-click and shortcuts (optional - prevent cheating)
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.keyCode == 67 || e.keyCode == 86 || e.keyCode == 85 || e.keyCode == 117)) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>