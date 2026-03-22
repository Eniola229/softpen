@include('components.header') 
  <body>
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>

    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      @include('components.nav') 
      @include('components.student-nav') 

      <div class="page-wrapper">
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Student Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">SoftPen</li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>

        <div class="container-fluid">

          {{-- Flash messages --}}
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif
          @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('message') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Quick nav cards --}}
          <div class="row mb-4">
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <h1 class="font-light text-white"><i class="mdi mdi-view-dashboard"></i></h1>
                  <h6 class="text-white">Dashboard</h6>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <a href="{{ url('student/results') }}">
                <div class="card card-hover">
                  <div class="box bg-info text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-account fs-3 mb-1 font-16"></i></h1>
                    <h6 class="text-white">Result's / Profile</h6>
                  </div>
                </div>
              </a>
            </div>
          </div>

          {{-- Exam Code Entry Section --}}
          <div class="row justify-content-center">
            <div class="col-lg-10">
              <div class="exam-hero-card">

                {{-- Floating animated background items --}}
                <div class="floating-items" aria-hidden="true">
                  <span class="float-item fi-1">✏️</span>
                  <span class="float-item fi-2">📐</span>
                  <span class="float-item fi-3">📚</span>
                  <span class="float-item fi-4">🖊️</span>
                  <span class="float-item fi-5">📝</span>
                  <span class="float-item fi-6">🔬</span>
                  <span class="float-item fi-7">📏</span>
                  <span class="float-item fi-8">🧮</span>
                  <span class="float-item fi-9">⚗️</span>
                  <span class="float-item fi-10">📖</span>
                  <span class="float-item fi-11">🖋️</span>
                  <span class="float-item fi-12">🔭</span>
                  <!-- Formula animations -->
                  <span class="float-formula f-1">E = mc²</span>
                  <span class="float-formula f-2">∑xᵢ/n</span>
                  <span class="float-formula f-3">a² + b² = c²</span>
                  <span class="float-formula f-4">∫f(x)dx</span>
                  <span class="float-formula f-5">F = ma</span>
                  <span class="float-formula f-6">V = IR</span>
                </div>

                <div class="exam-hero-content">
                  <div class="exam-icon-wrap mb-3">
                    <div class="exam-pulse-ring"></div>
                    <i class="mdi mdi-shield-key exam-shield-icon"></i>
                  </div>

                  <h2 class="exam-hero-title">Ready to take your exam?</h2>
                  <p class="exam-hero-subtitle">Enter the exam code provided by your teacher to begin</p>

                  @if ($errors->has('exam_code'))
                    <div class="alert alert-danger mb-3">{{ $errors->first('exam_code') }}</div>
                  @endif

                  <form action="{{ route('student.exam.enter-code') }}" method="POST" id="examCodeForm">
                    @csrf
                    <div class="code-input-group">
                      <div class="code-input-wrapper">
                        <i class="mdi mdi-key-variant code-input-icon"></i>
                        <input
                          type="text"
                          name="exam_code"
                          id="examCodeInput"
                          class="code-input"
                          placeholder="e.g. SOFTPEN123456"
                          maxlength="30"
                          autocomplete="off"
                          autofocus
                          value="{{ old('exam_code') }}"
                          required
                        />
                      </div>
                      <button type="submit" class="code-submit-btn" id="submitBtn">
                        <span class="btn-text">Find Exam</span>
                        <i class="mdi mdi-arrow-right btn-icon"></i>
                      </button>
                    </div>
                    <p class="code-hint">The exam code starts with your school name (e.g. <strong>SOFTPEN</strong>) followed by 6 digits</p>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>{{-- end container --}}

        <footer class="footer text-center">
          All Rights Reserved by SoftPenTech | Developed by SoftpenTech
        </footer>
      </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>

    <script>
      // Auto-uppercase exam code as user types
      document.getElementById('examCodeInput').addEventListener('input', function() {
        let val = this.value.toUpperCase();
        this.value = val;
      });

      // Button loading state on submit
      document.getElementById('examCodeForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<span class="btn-text">Searching...</span><i class="mdi mdi-loading mdi-spin btn-icon"></i>';
        btn.disabled = true;
      });
    </script>

<style>
/* ===== Exam Hero Card ===== */
.exam-hero-card {
  position: relative;
  background: linear-gradient(135deg, #1a237e 0%, #0d47a1 40%, #006064 100%);
  border-radius: 20px;
  padding: 60px 40px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(13, 71, 161, 0.35);
  margin-bottom: 30px;
}

.exam-hero-content {
  position: relative;
  z-index: 2;
  text-align: center;
}

/* ===== Floating Animated Items ===== */
.floating-items {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 1;
  overflow: hidden;
}

.float-item {
  position: absolute;
  font-size: 1.8rem;
  opacity: 0.18;
  animation: floatUpDown linear infinite;
  user-select: none;
}

.float-item.fi-1  { left: 5%;  bottom: -10%; animation-duration: 12s; animation-delay: 0s;   font-size: 2rem; }
.float-item.fi-2  { left: 12%; bottom: -10%; animation-duration: 15s; animation-delay: 2s; }
.float-item.fi-3  { left: 22%; bottom: -10%; animation-duration: 10s; animation-delay: 4s;   font-size: 2.2rem; }
.float-item.fi-4  { left: 33%; bottom: -10%; animation-duration: 13s; animation-delay: 1s; }
.float-item.fi-5  { left: 45%; bottom: -10%; animation-duration: 11s; animation-delay: 3s;   font-size: 1.6rem; }
.float-item.fi-6  { left: 57%; bottom: -10%; animation-duration: 14s; animation-delay: 5s;   font-size: 2.1rem; }
.float-item.fi-7  { left: 67%; bottom: -10%; animation-duration: 9s;  animation-delay: 0.5s; }
.float-item.fi-8  { left: 76%; bottom: -10%; animation-duration: 16s; animation-delay: 2.5s; font-size: 2rem; }
.float-item.fi-9  { left: 85%; bottom: -10%; animation-duration: 12s; animation-delay: 1.5s; font-size: 2.2rem; }
.float-item.fi-10 { left: 91%; bottom: -10%; animation-duration: 11s; animation-delay: 3.5s; }
.float-item.fi-11 { left: 3%;  bottom: -10%; animation-duration: 17s; animation-delay: 6s; }
.float-item.fi-12 { left: 50%; bottom: -10%; animation-duration: 13s; animation-delay: 7s;   font-size: 2.2rem; }

@keyframes floatUpDown {
  0%   { transform: translateY(0)   rotate(0deg);   opacity: 0; }
  10%  { opacity: 0.18; }
  90%  { opacity: 0.18; }
  100% { transform: translateY(-120vh) rotate(360deg); opacity: 0; }
}

/* ===== Floating Formulas ===== */
.float-formula {
  position: absolute;
  font-family: 'Courier New', monospace;
  font-size: 0.85rem;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.15);
  animation: floatFormula linear infinite;
  white-space: nowrap;
  user-select: none;
}

.float-formula.f-1 { left: 8%;  bottom: -5%; animation-duration: 18s; animation-delay: 0s; }
.float-formula.f-2 { left: 28%; bottom: -5%; animation-duration: 20s; animation-delay: 4s; }
.float-formula.f-3 { left: 48%; bottom: -5%; animation-duration: 16s; animation-delay: 8s; }
.float-formula.f-4 { left: 65%; bottom: -5%; animation-duration: 22s; animation-delay: 2s; }
.float-formula.f-5 { left: 78%; bottom: -5%; animation-duration: 19s; animation-delay: 6s; }
.float-formula.f-6 { left: 90%; bottom: -5%; animation-duration: 17s; animation-delay: 10s; }

@keyframes floatFormula {
  0%   { transform: translateY(0);      opacity: 0; }
  10%  { opacity: 1; }
  90%  { opacity: 1; }
  100% { transform: translateY(-110vh); opacity: 0; }
}

/* ===== Shield Icon / Pulse ===== */
.exam-icon-wrap {
  position: relative;
  display: inline-block;
  width: 80px;
  height: 80px;
  margin: 0 auto;
}

.exam-shield-icon {
  font-size: 3.5rem;
  color: #ffffff;
  position: relative;
  z-index: 2;
  line-height: 80px;
}

.exam-pulse-ring {
  position: absolute;
  inset: -10px;
  border-radius: 50%;
  border: 3px solid rgba(255, 255, 255, 0.4);
  animation: pulseRing 2s ease-out infinite;
}

@keyframes pulseRing {
  0%   { transform: scale(0.8); opacity: 1; }
  100% { transform: scale(1.6); opacity: 0; }
}

/* ===== Text ===== */
.exam-hero-title {
  color: #ffffff;
  font-size: 1.9rem;
  font-weight: 700;
  margin-bottom: 8px;
  letter-spacing: -0.5px;
}

.exam-hero-subtitle {
  color: rgba(255, 255, 255, 0.75);
  font-size: 1rem;
  margin-bottom: 35px;
}

/* ===== Code Input Group ===== */
.code-input-group {
  display: flex;
  gap: 12px;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  margin-bottom: 16px;
}

.code-input-wrapper {
  position: relative;
  flex: 1;
  max-width: 380px;
}

.code-input-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 1.3rem;
  color: #90caf9;
  z-index: 2;
}

.code-input {
  width: 100%;
  padding: 16px 20px 16px 48px;
  font-size: 1.15rem;
  font-weight: 600;
  letter-spacing: 2px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.12);
  color: #ffffff;
  outline: none;
  transition: all 0.3s;
  backdrop-filter: blur(10px);
}

.code-input::placeholder { color: rgba(255, 255, 255, 0.45); letter-spacing: 1px; font-weight: 400; }

.code-input:focus {
  border-color: #64b5f6;
  background: rgba(255, 255, 255, 0.2);
  box-shadow: 0 0 0 4px rgba(100, 181, 246, 0.25);
}

.code-submit-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 16px 28px;
  background: #ffffff;
  color: #0d47a1;
  border: none;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s;
  white-space: nowrap;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.code-submit-btn:hover:not(:disabled) {
  background: #e3f2fd;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.25);
}

.code-submit-btn:disabled { opacity: 0.7; cursor: not-allowed; }

.btn-icon { font-size: 1.2rem; }

.code-hint {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.85rem;
  margin-top: 4px;
}

.code-hint strong { color: rgba(255, 255, 255, 0.9); }
</style>

  </body>
</html>