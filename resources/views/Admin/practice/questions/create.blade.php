@include('components.header')
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<script src="https://cdn.tiny.cloud/1/9lcsi17by61qxgfug4h9ns3wl0mkdwithf1yovboozc6qd27/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>




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
    @include('components.side-nav')

    <div class="page-wrapper">
      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Add Practice Question</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('exams.index', $class->id) }}">{{ $class->name }}</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('exams.show', [$class->id, $exam->id]) }}">{{ $exam->title }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Add Question</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('message') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title">Add Question to: {{ $exam->title }}</h1>
            <h6 class="card-subtitle mb-4">Create a new practice question with explanation</h6>

            <form action="{{ route('questions.store', [$class->id, $exam->id]) }}" method="POST" enctype="multipart/form-data" id="questionForm">
              @csrf

              <!-- Question Text -->
              <div class="form-group mb-3">
                <label for="question_text">Question Text *</label>
                    <textarea
                        class="form-control rich-editor"
                        id="question_text"
                        name="question_text"
                        rows="4"
                        required
                    >
                    {{ old('question_text', $question->question_text ?? '') }}
                    </textarea>
              </div>

              <!-- Question Image -->
              <div class="form-group mb-3">
                <label for="question_image">Question Image (Optional)</label>
                <input type="file" id="question_image" name="question_image" class="form-control" accept="image/*" onchange="previewImage(event, 'questionImagePreview')">
                <small class="text-muted">Upload an image if needed for this question</small>
                <div id="questionImagePreview" class="mt-2"></div>
              </div>

              <!-- Question Type -->
              <div class="form-group mb-3">
                <label for="question_type">Question Type *</label>
                <select id="question_type" name="question_type" class="form-control" required onchange="toggleOptionsSection()">
                  <option value="">-- Select Type --</option>
                  <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                  <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                  <!-- <option value="short_answer" {{ old('question_type') == 'short_answer' ? 'selected' : '' }}>Short Answer</option> -->
                </select>
              </div>

              <!-- Mark -->
              <div class="form-group mb-3">
                <label for="mark">Mark *</label>
                <input type="number" id="mark" name="mark" class="form-control" placeholder="e.g., 2" value="{{ old('mark', 1) }}" min="1" required>
              </div>

              <!-- Order -->
              <div class="form-group mb-3">
                <label for="order">Question Order *</label>
                <input type="number" id="order" name="order" class="form-control" placeholder="e.g., 1" value="{{ old('order', $nextOrder) }}" min="1" required>
              </div>

              <!-- Explanation (Important for Practice) -->
              <div class="form-group mb-3">
                <label for="explanation">Explanation * <span class="badge bg-info">Practice Mode</span></label>
                    <textarea
                        class="form-control rich-editor"
                        id="explanation"
                        name="explanation"
                        rows="5"
                        required
                    >
                    {{ old('explanation', $question->explanation ?? '') }}
                    </textarea>


                <small class="text-muted">This explanation will be shown to students after they answer the question</small>
              </div>

              <!-- Hint (Optional) -->
              <div class="form-group mb-3">
                <label for="hint">Hint (Optional)</label>
                <textarea id="hint" name="hint" class="form-control" rows="2" placeholder="Provide a helpful hint (optional)">{{ old('hint') }}</textarea>
                <small class="text-muted">A hint that students can view before answering</small>
              </div>

              <!-- Options Section (for Multiple Choice and True/False) -->
              <div id="optionsSection" style="display: none;">
                <h5 class="mt-4">Answer Options</h5>
                <div id="optionsContainer">
                  <!-- Options will be added here dynamically -->
                </div>
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addOption()">
                  <i class="fas fa-plus"></i> Add Option
                </button>
              </div>

              <!-- Submit Buttons -->
              <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Save Question
                </button>
                <a href="{{ route('exams.show', [$class->id, $exam->id]) }}" class="btn btn-secondary">
                  <i class="fas fa-times"></i> Cancel
                </a>
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
  <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
  <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
  <script src="{{ asset('dist/js/waves.js') }}"></script>
  <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('dist/js/custom.min.js') }}"></script>

  <script>
    let optionCount = 0;

    function toggleOptionsSection() {
      const questionType = document.getElementById('question_type').value;
      const optionsSection = document.getElementById('optionsSection');
      const optionsContainer = document.getElementById('optionsContainer');

      if (questionType === 'multiple_choice') {
        optionsSection.style.display = 'block';
        optionsContainer.innerHTML = '';
        optionCount = 0;
        // Add 4 default options
        for (let i = 0; i < 4; i++) {
          addOption();
        }
      } else if (questionType === 'true_false') {
        optionsSection.style.display = 'block';
        optionsContainer.innerHTML = '';
        optionCount = 0;
        addTrueFalseOptions();
      } else {
        optionsSection.style.display = 'none';
        optionsContainer.innerHTML = '';
        optionCount = 0;
      }
    }

    function addOption() {
      const optionsContainer = document.getElementById('optionsContainer');
      const optionDiv = document.createElement('div');
      optionDiv.className = 'card mb-3 p-3';
      optionDiv.id = `option-${optionCount}`;
      
      optionDiv.innerHTML = `
        <div class="row">
          <div class="col-md-6">
            <label>Option Text *</label>
            <input type="text" name="options[${optionCount}][option_text]" class="form-control" placeholder="Enter option text" required>
          </div>
          <div class="col-md-3">
            <label>Option Image (Optional)</label>
            <input type="file" name="options[${optionCount}][image]" class="form-control" accept="image/*" onchange="previewImage(event, 'optionImagePreview${optionCount}')">
            <div id="optionImagePreview${optionCount}" class="mt-2"></div>
          </div>
          <div class="col-md-2">
            <label>Correct Answer?</label>
            <div class="form-check">
              <input type="radio" name="options[${optionCount}][is_correct]" value="1" class="form-check-input" id="correct${optionCount}">
              <label class="form-check-label" for="correct${optionCount}">Yes</label>
            </div>
          </div>
          <div class="col-md-1">
            <label>&nbsp;</label>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(${optionCount})">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      `;
      
      optionsContainer.appendChild(optionDiv);
      optionCount++;
    }

    function addTrueFalseOptions() {
      const optionsContainer = document.getElementById('optionsContainer');
      
      // True Option
      const trueDiv = document.createElement('div');
      trueDiv.className = 'card mb-3 p-3';
      trueDiv.innerHTML = `
        <div class="row">
          <div class="col-md-9">
            <label>Option Text</label>
            <input type="text" name="options[0][option_text]" class="form-control" value="True" readonly required>
          </div>
          <div class="col-md-3">
            <label>Correct Answer?</label>
            <div class="form-check">
              <input type="radio" name="options[0][is_correct]" value="1" class="form-check-input" id="correctTrue">
              <label class="form-check-label" for="correctTrue">Yes</label>
            </div>
          </div>
        </div>
      `;
      
      // False Option
      const falseDiv = document.createElement('div');
      falseDiv.className = 'card mb-3 p-3';
      falseDiv.innerHTML = `
        <div class="row">
          <div class="col-md-9">
            <label>Option Text</label>
            <input type="text" name="options[1][option_text]" class="form-control" value="False" readonly required>
          </div>
          <div class="col-md-3">
            <label>Correct Answer?</label>
            <div class="form-check">
              <input type="radio" name="options[1][is_correct]" value="1" class="form-check-input" id="correctFalse">
              <label class="form-check-label" for="correctFalse">Yes</label>
            </div>
          </div>
        </div>
      `;
      
      optionsContainer.appendChild(trueDiv);
      optionsContainer.appendChild(falseDiv);
      optionCount = 2;
    }

    function removeOption(index) {
      const optionDiv = document.getElementById(`option-${index}`);
      if (optionDiv) {
        optionDiv.remove();
      }
    }

    function previewImage(event, previewId) {
      const preview = document.getElementById(previewId);
      const file = event.target.files[0];
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
        }
        reader.readAsDataURL(file);
      }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
      const questionType = document.getElementById('question_type').value;
      if (questionType) {
        toggleOptionsSection();
      }
    });
  </script>
<script>
tinymce.init({
    selector: 'textarea.rich-editor',
    height: 400,
    menubar: false,
   plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
     toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | table | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
    toolbar_mode: 'sliding',
    contextmenu: 'link image table',
    branding: false
});
</script>

</body>
</html>