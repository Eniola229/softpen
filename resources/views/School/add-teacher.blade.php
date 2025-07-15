@include('components.header') 
<link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
  <body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      @include('components.nav') 
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      @include('components.school-nav') 
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title"></h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Add Schools
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

          

        <div class="container-fluid">
          <!-- ============================================================== -->
          <!-- Start Page Content -->
          <!-- ============================================================== -->
          <div class="card">
            <div class="card-body wizard-content">
              <h1 class="card-title">Add a New Student</h1>
              <h6 class="card-subtitle"></h6>
                <form id="example-form" action="{{ route('school/add/teacher') }}" method="post" class="mt-5" enctype="multipart/form-data"> 
                @csrf
                <div>
                    <h3>Privacy</h3>
                    <section>
                    <label for="userName">Teacher Name *</label>
                    <input
                        id="userName"
                        name="name"
                        type="text"
                        class="required form-control"
                        value="{{ old('name') }}"
                    />

                    <label for="password">Password *</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="required form-control"
                    />

                    <label for="confirm">Confirm Password *</label>
                    <input
                        id="confirm"
                        name="confirm"
                        type="password"
                        class="required form-control"
                    />

                    <p>(*) Mandatory</p>
                    </section>

                    <h3>Profile</h3>
                    <section>
                    <label for="email">Email *</label>
                    <input
                        id="email"
                        name="email"
                        type="text"
                        class="required email form-control"
                        value="{{ old('email') }}"
                    />

                    <label for="mobile">Mobile</label>
                    <input
                        id="mobile"
                        name="mobile"
                        type="number"
                        class="form-control"
                        value="{{ old('mobile') }}"
                    />

                    <label for="address">Address</label>
                    <input
                        id="address"
                        name="address"
                        type="text"
                        class="form-control"
                        value="{{ old('address') }}"
                    />

                    <label for="class">Class</label>
                    <select id="class" name="class" class="form-control">
                        <option value="">-- Select Class --</option>
                        @for ($i = 1; $i <= 6; $i++)
                        <option value="Primary {{ $i }}" {{ old('class') == "Primary $i" ? 'selected' : '' }}>Primary {{ $i }}</option>
                        @endfor
                        @for ($i = 1; $i <= 3; $i++)
                        <option value="SS{{ $i }}" {{ old('class') == "SS$i" ? 'selected' : '' }}>SS{{ $i }}</option>
                        @endfor
                    </select>

                    <label for="department">Department</label>
                    <select id="department" name="department" class="form-control">
                        <option value="">-- Select Department --</option>
                        <option value="ART" {{ old('department') == 'ART' ? 'selected' : '' }}>ART</option>
                        <option value="SCIENCE" {{ old('department') == 'SCIENCE' ? 'selected' : '' }}>SCIENCE</option>
                        <option value="COMMERCIAL" {{ old('department') == 'COMMERCIAL' ? 'selected' : '' }}>COMMERCIAL</option>
                    </select>

                    <label for="subject">Subject</label>
                    <input
                        id="subject"
                        name="subject"
                        type="text"
                        class="form-control"
                        value="{{ old('subject') }}"
                    />

                    <p>(*) Mandatory</p>
                    </section>

                    <h3>Upload Picture</h3>
                    <section>
                    <label for="avatar">Teacher Picture *</label>
                    <input id="avatar" name="avatar" type="file" class="form-control" accept="image/*">
                    <p>(*) Mandatory</p>
                    </section>

                    <h3>Finish</h3>
                    <section>
                    <input
                        id="acceptTerms"
                        name="acceptTerms"
                        type="checkbox"
                        class="required"
                    />
                    <label for="acceptTerms">
                        I agree with the Terms and Conditions.
                    </label>
                    <button class="btn btn-primary" type="submit">Create</button>
                    </section>
                </div>
                </form>

            </div>
          </div>
          <!-- ============================================================== -->
          <!-- End PAge Content -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- Right sidebar -->
          <!-- ============================================================== -->
          <!-- .right-sidebar -->
          <!-- ============================================================== -->
          <!-- End Right sidebar -->
          <!-- ============================================================== -->
        </div>

     
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            All Rights Reserved by SoftPen Technologies | Deleoped by Softpen Tech
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- Slimscrollbar JavaScript -->
<script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<!-- Wave Effects -->
<script src="{{ asset('dist/js/waves.js') }}"></script>
<!-- Menu Sidebar -->
<script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
<!-- Custom JavaScript -->
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<!-- This Page JS -->
<script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

    <script>
      // Basic Example with form
      var form = $("#example-form");
      form.validate({
        errorPlacement: function errorPlacement(error, element) {
          element.before(error);
        },
        rules: {
          confirm: {
            equalTo: "#password",
          },
        },
      });
      form.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {
          form.validate().settings.ignore = ":disabled,:hidden";
          return form.valid();
        },
        onFinishing: function (event, currentIndex) {
          form.validate().settings.ignore = ":disabled";
          return form.valid();
        },
        onFinished: function (event, currentIndex) {
          alert("Submitted!");
        },
      });
    </script>
  </body>
</html>
