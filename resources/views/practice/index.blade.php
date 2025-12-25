@include('components.p-header') 
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
    @include('components.p-side-nav')

    <div class="page-wrapper">
      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Practice Exams</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Practice</li>
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

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="container-fluid">
        <!-- Balance Card -->
        <div class="row">
          <div class="col-md-12">
            <div class="card bg-info text-white mb-4">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h5 class="card-title text-white">Your Balance</h5>
                    <h2 class="text-white">₦{{ number_format($user->balance, 2) }}</h2>
                    <p class="mb-0">Each practice exam costs ₦20</p>
                  </div>
                  <div>
                    <i class="mdi mdi-wallet" style="font-size: 60px;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Box -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="input-group">
                  <span class="input-group-text bg-info text-white">
                    <i class="mdi mdi-magnify"></i>
                  </span>
                  <input type="text" id="searchInput" class="form-control" 
                         placeholder="Search for class or subject...">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Practice Classes -->
        <div class="row">
          <div class="col-12">
            <h4 class="mb-3">Select a Class and Subject to Practice</h4>
          </div>

          @if($classes->count() > 0)
            <div id="classContainer" class="col-12">
              @php
                // Group classes by name
                $groupedClasses = $classes->groupBy('name');
                
                // Custom sorting function
                $sortedClasses = $groupedClasses->sortBy(function($classGroup, $className) {
                    $name = strtoupper(trim($className));
                    
                    // SS classes (priority 1)
                    if (preg_match('/^SS\s*[1-3]?/', $name) || preg_match('/SENIOR\s*SECONDARY/', $name)) {
                        // Extract number if exists (SS3 = 1, SS2 = 2, SS1 = 3)
                        if (preg_match('/SS\s*(\d)/', $name, $matches)) {
                            return 10 - (int)$matches[1]; // SS3=9, SS2=8, SS1=7
                        }
                        return 5; // Generic SS without number
                    }
                    
                    // JSS classes (priority 2)
                    if (preg_match('/^JSS\s*[1-3]?/', $name) || preg_match('/JUNIOR\s*SECONDARY/', $name)) {
                        // Extract number if exists (JSS3 = 1, JSS2 = 2, JSS1 = 3)
                        if (preg_match('/JSS\s*(\d)/', $name, $matches)) {
                            return 20 - (int)$matches[1]; // JSS3=19, JSS2=18, JSS1=17
                        }
                        return 15; // Generic JSS without number
                    }
                    
                    // Primary classes (priority 3)
                    if (preg_match('/^PRIMARY/', $name) || preg_match('/^PRY/', $name) || preg_match('/^P\s*[1-6]/', $name)) {
                        // Extract number if exists (Primary 6 = 1, Primary 5 = 2, etc.)
                        if (preg_match('/(?:PRIMARY|PRY|P)\s*(\d)/', $name, $matches)) {
                            return 30 - (int)$matches[1]; // Primary 6=29, Primary 5=28, etc.
                        }
                        return 25; // Generic Primary without number
                    }
                    
                    // Others (priority 4)
                    return 100;
                }, SORT_REGULAR);
              @endphp

              @foreach($sortedClasses as $className => $classGroup)
                <div class="card mb-3 class-card">
                  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" 
                       style="cursor: pointer;" 
                       data-bs-toggle="collapse" 
                       data-bs-target="#collapse{{ Str::slug($className) }}">
                    <div>
                      <i class="mdi mdi-school me-2"></i>
                      <strong class="class-name">{{ $className }}</strong>
                      <span class="badge bg-light text-dark ms-2">{{ $classGroup->count() }} Subject(s)</span>
                    </div>
                    <i class="mdi mdi-chevron-down"></i>
                  </div>
                  <div id="collapse{{ Str::slug($className) }}" class="collapse">
                    <div class="card-body">
                      <div class="row">
                        @foreach($classGroup as $class)
                          <div class="col-md-6 col-lg-4 mb-3 subject-item" 
                               data-class="{{ strtolower($className) }}" 
                               data-subject="{{ strtolower($class->subject) }}">
                            <a href="{{ route('practice.exams', $class->id) }}" class="text-decoration-none">
                              <div class="card card-hover border">
                                <div class="card-body">
                                  <div class="d-flex align-items-center">
                                    <div class="me-3">
                                      <i class="mdi mdi-book-open-variant" style="font-size: 35px; color: #f59e0b;"></i>
                                    </div>
                                    <div>
                                      <h6 class="mb-0 subject-name">{{ $class->subject }}</h6>
                                      <small class="text-muted">Click to start practice</small>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="col-12" style="display: none;">
              <div class="alert alert-warning">
                <i class="mdi mdi-alert-circle-outline me-2"></i>
                <strong>No results found</strong><br>
                Try searching with different keywords.
              </div>
            </div>
          @else
            <div class="col-12">
              <div class="alert alert-info">
                <strong>No Practice Classes Available</strong><br>
                There are currently no practice classes available. Please check back later.
              </div>
            </div>
          @endif
        </div>

        <!-- My Attempts -->
        <div class="row mt-4">
          <div class="col-12">
            <a href="{{ route('practice.attempts') }}" class="btn btn-primary">
              <i class="mdi mdi-history"></i> View My Attempt History
            </a>
          </div>
        </div>
      </div>

      <footer class="footer text-center">
        All Rights Reserved by SoftPen Technologies | Develop by Softpen Tech
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
    $(document).ready(function() {
      // Search functionality
      $('#searchInput').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        let visibleCount = 0;

        if (searchTerm === '') {
          // Show all if search is empty
          $('.class-card').show();
          $('.subject-item').show();
          $('#noResults').hide();
          return;
        }

        // Hide all cards initially
        $('.class-card').hide();
        $('.subject-item').hide();

        // Search through each class card
        $('.class-card').each(function() {
          const $card = $(this);
          const className = $card.find('.class-name').text().toLowerCase();
          let hasVisibleSubjects = false;

          // Check if class name matches
          const classMatches = className.includes(searchTerm);

          // Check subjects within this class
          $card.find('.subject-item').each(function() {
            const $subject = $(this);
            const subjectName = $subject.data('subject');
            
            if (classMatches || subjectName.includes(searchTerm)) {
              $subject.show();
              hasVisibleSubjects = true;
            }
          });

          // Show card if it has visible subjects
          if (hasVisibleSubjects) {
            $card.show();
            // Auto-expand the accordion
            $card.find('.collapse').addClass('show');
            visibleCount++;
          }
        });

        // Show no results message if nothing found
        if (visibleCount === 0) {
          $('#noResults').show();
        } else {
          $('#noResults').hide();
        }
      });

      // Clear search when clicking on a class header (optional enhancement)
      $('.card-header[data-bs-toggle="collapse"]').on('click', function() {
        const searchVal = $('#searchInput').val();
        if (searchVal === '') {
          // Toggle chevron icon
          const $icon = $(this).find('.mdi-chevron-down, .mdi-chevron-up');
          $icon.toggleClass('mdi-chevron-down mdi-chevron-up');
        }
      });
    });
  </script>

  <style>
    .card-hover {
      transition: all 0.3s ease;
    }
    
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .card-header[data-bs-toggle="collapse"]:hover {
      background-color: #0d6efd !important;
    }

    #searchInput:focus {
      border-color: #17a2b8;
      box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
    }

    .subject-item {
      transition: all 0.3s ease;
    }
  </style>
</body>
</html>