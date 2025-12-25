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
            <h4 class="page-title">Transaction History</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('practice.index') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="me-3">
                    <div class="rounded-circle bg-light-success p-3">
                      <i class="mdi mdi-arrow-down text-success" style="font-size: 24px;"></i>
                    </div>
                  </div>
                  <div>
                    <h3 class="mb-0">₦{{ number_format($stats['total_credit'], 2) }}</h3>
                    <p class="text-muted mb-0">Total Credits</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="me-3">
                    <div class="rounded-circle bg-light-danger p-3">
                      <i class="mdi mdi-arrow-up text-danger" style="font-size: 24px;"></i>
                    </div>
                  </div>
                  <div>
                    <h3 class="mb-0">₦{{ number_format($stats['total_debit'], 2) }}</h3>
                    <p class="text-muted mb-0">Total Debits</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="me-3">
                    <div class="rounded-circle bg-light-warning p-3">
                      <i class="mdi mdi-clock-outline text-warning" style="font-size: 24px;"></i>
                    </div>
                  </div>
                  <div>
                    <h3 class="mb-0">{{ $stats['pending_count'] }}</h3>
                    <p class="text-muted mb-0">Pending</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="me-3">
                    <div class="rounded-circle bg-light-info p-3">
                      <i class="mdi mdi-check-circle text-info" style="font-size: 24px;"></i>
                    </div>
                  </div>
                  <div>
                    <h3 class="mb-0">{{ $stats['completed_count'] }}</h3>
                    <p class="text-muted mb-0">Completed</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Current Balance -->
        <div class="row">
          <div class="col-12">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h4 class="text-white mb-1">Current Balance</h4>
                    <h2 class="text-white mb-0">₦{{ number_format($user->balance, 2) }}</h2>
                  </div>
                  <div>
                    <i class="mdi mdi-wallet" style="font-size: 48px; opacity: 0.5;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Filter Transactions</h5>
                <form method="GET" action="{{ route('transactions.history') }}" class="row g-3">
                  <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                      <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                      <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                      <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                      <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                      <option value="course_purchase" {{ request('category') == 'course_purchase' ? 'selected' : '' }}>Course Purchase</option>
                      <option value="wallet_topup" {{ request('category') == 'wallet_topup' ? 'selected' : '' }}>Wallet Top-up</option>
                      <option value="withdrawal" {{ request('category') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                      <option value="course_refund" {{ request('category') == 'course_refund' ? 'selected' : '' }}>Course Refund</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                      <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                      <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                      <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                      <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                  </div>
                  <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                      <i class="mdi mdi-filter"></i>
                    </button>
                  </div>
                </form>
                @if(request()->hasAny(['type', 'category', 'status', 'date_from', 'date_to']))
                  <div class="mt-3">
                    <a href="{{ route('transactions.history') }}" class="btn btn-sm btn-secondary">
                      <i class="mdi mdi-close"></i> Clear Filters
                    </a>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Transactions Table -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4 class="card-title mb-0">All Transactions</h4>
                  <div class="text-muted">
                    <small>Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} transactions</small>
                  </div>
                </div>

                @if($transactions->isEmpty())
                  <div class="alert alert-info text-center py-5">
                    <i class="mdi mdi-information-outline" style="font-size: 48px;"></i>
                    <h4 class="mt-3">No Transactions Found</h4>
                    <p>You don't have any transactions yet. Start practicing exams or top up your wallet to see transactions here.</p>
                    <a href="{{ route('practice.index') }}" class="btn btn-primary mt-2">
                      <i class="mdi mdi-play"></i> Start Practicing
                    </a>
                  </div>
                @else
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead class="bg-light">
                        <tr>
                          <th>Reference</th>
                          <th>Date</th>
                          <th>Type</th>
                          <th>Category</th>
                          <th>Description</th>
                          <th>Amount</th>
                          <th>Balance After</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($transactions as $transaction)
                          <tr>
                            <td>
                              <small class="text-muted">{{ $transaction->reference }}</small>
                            </td>
                            <td>
                              {{ $transaction->created_at->format('M d, Y') }}<br>
                              <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                              @if($transaction->type === 'credit')
                                <span class="badge bg-success">
                                  <i class="mdi mdi-arrow-down"></i> Credit
                                </span>
                              @else
                                <span class="badge bg-danger">
                                  <i class="mdi mdi-arrow-up"></i> Debit
                                </span>
                              @endif
                            </td>
                            <td>
                              <span class="badge bg-secondary">
                                {{ ucwords(str_replace('_', ' ', $transaction->category)) }}
                              </span>
                            </td>
                            <td>
                              {{ $transaction->description ?? 'N/A' }}
                              @if($transaction->payment_method)
                                <br><small class="text-muted">via {{ ucfirst($transaction->payment_method) }}</small>
                              @endif
                            </td>
                            <td>
                              <strong class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->type === 'credit' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                              </strong>
                            </td>
                            <td>
                              ₦{{ number_format($transaction->balance_after, 2) }}
                            </td>
                            <td>
                              @if($transaction->status === 'completed')
                                <span class="badge bg-success">
                                  <i class="mdi mdi-check-circle"></i> Completed
                                </span>
                              @elseif($transaction->status === 'pending')
                                <span class="badge bg-warning">
                                  <i class="mdi mdi-clock-outline"></i> Pending
                                </span>
                              @elseif($transaction->status === 'failed')
                                <span class="badge bg-danger">
                                  <i class="mdi mdi-close-circle"></i> Failed
                                </span>
                              @else
                                <span class="badge bg-secondary">
                                  <i class="mdi mdi-cancel"></i> Cancelled
                                </span>
                              @endif
                            </td>
                            <td>
                              <button class="btn btn-sm btn-info" 
                                      data-bs-toggle="modal" 
                                      data-bs-target="#transactionModal{{ $transaction->id }}">
                                <i class="mdi mdi-eye"></i> View
                              </button>
                            </td>
                          </tr>

                          <!-- Transaction Detail Modal -->
                          <div class="modal fade" id="transactionModal{{ $transaction->id }}" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Transaction Details</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="row mb-3">
                                    <div class="col-5"><strong>Reference:</strong></div>
                                    <div class="col-7">{{ $transaction->reference }}</div>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="col-5"><strong>Type:</strong></div>
                                    <div class="col-7">
                                      <span class="badge bg-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                        {{ ucfirst($transaction->type) }}
                                      </span>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="col-5"><strong>Category:</strong></div>
                                    <div class="col-7">{{ ucwords(str_replace('_', ' ', $transaction->category)) }}</div>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="col-5"><strong>Amount:</strong></div>
                                    <div class="col-7">
                                      <strong class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                        ₦{{ number_format($transaction->amount, 2) }}
                                      </strong>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="col-5"><strong>Balance Before:</strong></div>
                                    <div class="col-7">₦{{ number_format($transaction->balance_before, 2) }}</div>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="col-5"><strong>Balance After:</strong></div>
                                    <div class="col-7">₦{{ number_format($transaction->balance_after, 2) }}</div>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="col-5"><strong>Status:</strong></div>
                                    <div class="col-7">
                                      <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($transaction->status) }}
                                      </span>
                                    </div>
                                  </div>
                                  @if($transaction->payment_method)
                                    <div class="row mb-3">
                                      <div class="col-5"><strong>Payment Method:</strong></div>
                                      <div class="col-7">{{ ucfirst($transaction->payment_method) }}</div>
                                    </div>
                                  @endif
                                  @if($transaction->description)
                                    <div class="row mb-3">
                                      <div class="col-5"><strong>Description:</strong></div>
                                      <div class="col-7">{{ $transaction->description }}</div>
                                    </div>
                                  @endif
                                  <div class="row mb-3">
                                    <div class="col-5"><strong>Date:</strong></div>
                                    <div class="col-7">{{ $transaction->created_at->format('M d, Y h:i A') }}</div>
                                  </div>
                                  @if($transaction->metadata)
                                    <div class="row mb-3">
                                      <div class="col-12">
                                        <strong>Additional Info:</strong>
                                        <pre class="bg-light p-2 mt-2 rounded">{{ json_encode($transaction->metadata, JSON_PRETTY_PRINT) }}</pre>
                                      </div>
                                    </div>
                                  @endif
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <!-- Pagination -->
                  <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                      Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
                    </div>
                    <div>
                      {{ $transactions->links() }}
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