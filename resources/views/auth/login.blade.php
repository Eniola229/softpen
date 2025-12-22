<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="keywords"
      content="SchoolCode for online pratice exam"
    />
    <meta
      name="description"
      content="SchoolCode for online pratice exam"
    />
    <meta name="robots" content="noindex,nofollow" />
    <title>SchoolCode Africa</title>
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.jpg') }}">

  </head>

  <body>
    <div class="main-wrapper">
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
      <!-- Preloader - style you can find in spinners.css -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Login box.scss -->
      <!-- ============================================================== -->
      <div
        class="
          auth-wrapper
          d-flex
          no-block
          justify-content-center
          align-items-center
          bg-dark
        "
      >
        <div class="auth-box bg-dark border-top border-secondary">
          <div id="loginform">
            <div class="text-center pt-3 pb-3">
              <span class="db"><h1 style="color: white;">SchoolCode Africa</h1></span>
              <h3 style="color: white;">Login</h3>
            </div>
            <!-- Form -->
            <form
              class="form-horizontal mt-3"
              id="loginform"
              action="{{ route('login') }}"
              method="POST"
            >
            @csrf
                                 @if($errors->any())
                                    <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4">
                                        <ul class="list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>   
                                   @endif  
            @if (session('status'))
              <div class="alert alert-success mb-4">
                {{ session('status') }}
              </div>
            @endif
              <div class="row pb-4">
                <div class="col-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span
                        class="input-group-text bg-success text-white h-100"
                        id="basic-addon1"
                        ><i class="mdi mdi-account fs-4"></i
                      ></span>
                    </div>
                    <input
                      type="text"
                      class="form-control form-control-lg"
                      placeholder="Email"
                      aria-label="Email"
                      aria-describedby="basic-addon1"
                      required
                      name="email"
                    />
                  </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span
                        class="input-group-text bg-warning text-white h-100"
                        id="basic-addon2"
                        ><i class="mdi mdi-lock fs-4"></i
                      ></span>
                    </div>
                    <input
                      type="password"
                      class="form-control form-control-lg"
                      placeholder="Password"
                      aria-label="Password"
                      aria-describedby="basic-addon1"
                      required
                      name="password"
                    />
                  </div>
                                   <div class="text-right">
                  <span class="text-white"
                    >Don't have an account? <a href="{{ route('register') }}" style="color: white;">Click here to create an account</a></span
                  >
                </div>
                </div>
              </div>
              <div class="row border-top border-secondary">
                <div class="col-12">
                  <div class="form-group">
                    <div class="pt-3">
                      <button
                        class="btn btn-info"
                        id="to-recover"
                        type="button"
                      >
                        <i class="mdi mdi-lock fs-4 me-1"></i> Lost password?
                      </button>
                      <button
                        class="btn btn-success float-end text-white"
                        type="submit"
                      >
                        Login
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div id="recoverform">
            <div class="text-center">
              <span class="text-white"
                >Enter your e-mail address below and we will send you
                instructions how to recover a password.</span
              >
            </div>
            <div class="row mt-3">
              <!-- Form -->
              <form class="col-12" method="POST" action="{{ route('password.email') }}">
                @csrf
                <!-- email -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span
                      class="input-group-text bg-danger text-white h-100"
                      id="basic-addon1"
                      ><i class="mdi mdi-email fs-4"></i
                    ></span>
                  </div>
                  <input
                      type="email" 
                      class="form-control form-control-lg" 
                      placeholder="Email Address" 
                      name="email" 
                      value="{{ old('email') }}" 
                      required 
                      autofocus
                  />
                </div>
                <!-- pwd -->
                <div class="row mt-3 pt-3 border-top border-secondary">
                  <div class="col-12">
                    <a
                      class="btn btn-success text-white"
                      href="#"
                      id="to-login"
                      name="action"
                      >Back To Login</a
                    >
                    <button
                      class="btn btn-info float-end"
                      type="submit"
                      name="action"
                    >
                      Recover
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      $(".preloader").fadeOut();
      // ==============================================================
      // Login and Recover Password
      // ==============================================================
      $("#to-recover").on("click", function () {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
      });
      $("#to-login").click(function () {
        $("#recoverform").hide();
        $("#loginform").fadeIn();
      });
    </script>
  </body>
</html>
