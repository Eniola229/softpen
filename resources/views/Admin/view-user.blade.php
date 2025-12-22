@include('components.header')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}" />
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />

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
          <h4 class="page-title">View User</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="container my-5">

      <!-- User Info -->
      <div class="card mb-4 shadow">
        <div class="card-body">
          <h2 class="card-title">{{ $user->name }}</h2>
          <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
          <p class="card-text"><strong>Phone:</strong> {{ $user->mobile ?? 'N/A' }}</p>
          <p class="card-text"><strong>Class:</strong> {{ $user->class ?? 'N/A' }}</p>
          <p class="card-text"><strong>Age:</strong> {{ $user->age ?? 'N/A' }}</p>
          <p class="card-text"><strong>School:</strong> {{ $user->school ?? 'N/A' }}</p>
          <p class="card-text"><strong>Department:</strong> {{ $user->department ?? 'N/A' }}</p>
          <p class="card-text"><strong>Wallet Balance:</strong> {{ isset($user->balance) ? number_format($user->balance, 2) : 'N/A' }}</p>
        </div>
      </div>

      <!-- User Transactions -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Transactions</h5>
          <div class="table-responsive">
            <table id="transactions_table" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Category</th>
                  <th>Amount</th>
                  <th>Balance Before</th>
                  <th>Balance After</th>
                  <th>Reference</th>
                  <th>Status</th>
                  <th>Payment Method</th>
                  <th>Course</th>
                  <th>Description</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach($transactions as $txn)
                  <tr>
                    <td>{{ $txn->type }}</td>
                    <td>{{ $txn->category }}</td>
                    <td>{{ number_format($txn->amount, 2) }}</td>
                    <td>{{ number_format($txn->balance_before, 2) }}</td>
                    <td>{{ number_format($txn->balance_after, 2) }}</td>
                    <td>{{ $txn->reference }}</td>
                    <td>{{ $txn->status }}</td>
                    <td>{{ $txn->payment_method }}</td>
                    <td>{{ $txn->course_id ?? 'N/A' }}</td>
                    <td>{{ $txn->description ?? 'N/A' }}</td>
                    <td>{{ $txn->created_at->format('F j, Y g:i A') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>

    <footer class="footer text-center">
      All Rights Reserved by SoftPenTech | Developed by SoftpenTech
    </footer>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<script src="{{ asset('dist/js/waves.js') }}"></script>
<script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
<script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
<script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>

<script>
  $("#transactions_table").DataTable();
</script>

</body>
</html>
