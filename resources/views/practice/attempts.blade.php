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
            <h4 class="page-title">My Exam Attempts</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('practice.index') }}">Practice</a></li>
                  <li class="breadcrumb-item active" aria-current="page">My Attempts</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Exam History</h4>
                <p class="text-muted">View all your completed practice exams and their results</p>

                @if($attempts->isEmpty())
                  <div class="alert alert-info text-center py-5">
                    <i class="mdi mdi-information-outline" style="font-size: 48px;"></i>
                    <h4 class="mt-3">No Attempts Yet</h4>
                    <p>You haven't completed any practice exams yet. Start practicing to see your results here.</p>
                    <a href="{{ route('practice.index') }}" class="btn btn-primary mt-2">
                      <i class="mdi mdi-play"></i> Start Practicing
                    </a>
                  </div>
                @else
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead class="bg-light">
                        <tr>
                          <th>#</th>
                          <th>Exam Title</th>
                          <th>Subject</th>
                          <th>Score</th>
                          <th>Percentage</th>
                          <th>Status</th>
                          <th>Time Spent</th>
                          <th>Completed At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($attempts as $index => $attempt)
                          <tr>
                            <td>{{ $attempts->firstItem() + $index }}</td>
                            <td>
                              <strong>{{ $attempt->pExam->title ?? 'Deleted Exam' }}</strong>
                              @if(!$attempt->pExam)
                                <br><small class="text-danger">Exam no longer available</small>
                              @endif
                            </td>
                            <td>{{ $attempt->pExam->subject ?? 'N/A' }}</td>
                            <td>
                              <strong>{{ $attempt->score ?? 'N/A' }}</strong> / {{ $attempt->total_marks ?? 'N/A' }}
                            </td>
                            <td>
                              <span class="badge 
                                @if($attempt->percentage >= 80) bg-success
                                @elseif($attempt->percentage >= 60) bg-info
                                @elseif($attempt->percentage >= 40) bg-warning
                                @else bg-danger
                                @endif">
                                {{ number_format($attempt->percentage, 2) }}%
                              </span>
                            </td>
                            <td>
                              @if($attempt->passed)
                                <span class="badge bg-success">
                                  <i class="mdi mdi-check-circle"></i> Passed
                                </span>
                              @else
                                <span class="badge bg-danger">
                                  <i class="mdi mdi-close-circle"></i> Failed
                                </span>
                              @endif
                            </td>
                            <td>
                              <i class="mdi mdi-clock-outline text-muted"></i> {{ $attempt->time_spent ?? 'N/A' }}
                            </td>
                            <td>
                              {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : 'N/A' }}<br>
                              <small class="text-muted">{{ $attempt->completed_at ? $attempt->completed_at->format('h:i A') : '' }}</small>
                            </td>
                            <td>
                              @if($attempt->pExam)
                                <a href="{{ route('practice.result', [$attempt->pExam->p_class_id, $attempt->p_exam_id, $attempt->id]) }}" 
                                   class="btn btn-sm btn-primary">
                                  <i class="mdi mdi-eye"></i> View Details
                                </a>
                              @else
                                <button class="btn btn-sm btn-secondary" disabled>
                                  <i class="mdi mdi-alert"></i> Unavailable
                                </button>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <!-- Pagination -->
                  <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                      Showing {{ $attempts->firstItem() }} to {{ $attempts->lastItem() }} of {{ $attempts->total() }} attempts
                    </div>
                    <div>
                      {{ $attempts->links() }}
                    </div>
                  </div>

                  <!-- Statistics Cards -->
                  <div class="row mt-4">
                    <div class="col-md-3">
                      <div class="card bg-light">
                        <div class="card-body text-center">
                          <h3 class="text-primary">{{ $attempts->total() }}</h3>
                          <p class="text-muted mb-0">Total Attempts</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="card bg-light">
                        <div class="card-body text-center">
                          <h3 class="text-success">{{ $attempts->where('passed', true)->count() }}</h3>
                          <p class="text-muted mb-0">Passed</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="card bg-light">
                        <div class="card-body text-center">
                          <h3 class="text-danger">{{ $attempts->where('passed', false)->count() }}</h3>
                          <p class="text-muted mb-0">Failed</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="card bg-light">
                        <div class="card-body text-center">
                          <h3 class="text-info">
                            @php
                              $avgPercentage = $attempts->avg('percentage');
                            @endphp
                            {{ number_format($avgPercentage, 1) }}%
                          </h3>
                          <p class="text-muted mb-0">Average Score</p>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            </div>
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
</body>
</html>