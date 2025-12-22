<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="SchoolCode for online pratice exam" />
    <meta name="description" content="SchoolCode for online pratice exam" />
    <meta name="robots" content="noindex,nofollow" />
    <title>SchoolCode - Reset Password</title>
    <link href="../dist/css/style.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.jpg') }}">
  </head>

  <body>
    <div class="main-wrapper">
      <div class="preloader">
        <div class="lds-ripple">
          <div class="lds-pos"></div>
          <div class="lds-pos"></div>
        </div>
      </div>

      <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
        <div class="auth-box bg-dark border-top border-secondary">
          <div id="resetform">
            <div class="text-center pt-3 pb-3">
              <span class="db"><h1 style="color: white;">SchoolCode Africa</h1></span>
              <h3 style="color: white;">Reset Password</h3>
            </div>

            <form class="form-horizontal mt-3" method="POST" action="{{ route('password.store') }}">
              @csrf
              
              <!-- Password Reset Token -->
              <input type="hidden" name="token" value="{{ $request->route('token') }}">

              @if($errors->any())
                <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4">
                  <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>   
              @endif  

              <div class="row pb-4">
                <div class="col-12">
                  <!-- Email Address -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-danger text-white h-100" id="basic-addon1">
                        <i class="mdi mdi-email fs-4"></i>
                      </span>
                    </div>
                    <input 
                      type="email" 
                      class="form-control form-control-lg" 
                      placeholder="Email Address" 
                      name="email" 
                      value="{{ old('email', $request->email) }}" 
                      required 
                      autofocus 
                      autocomplete="username"
                    />
                  </div>

                  <!-- New Password -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-warning text-white h-100" id="basic-addon2">
                        <i class="mdi mdi-lock fs-4"></i>
                      </span>
                    </div>
                    <input 
                      type="password" 
                      class="form-control form-control-lg" 
                      placeholder="New Password" 
                      name="password" 
                      required 
                      autocomplete="new-password"
                    />
                  </div>

                  <!-- Confirm Password -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-success text-white h-100" id="basic-addon3">
                        <i class="mdi mdi-lock-check fs-4"></i>
                      </span>
                    </div>
                    <input 
                      type="password" 
                      class="form-control form-control-lg" 
                      placeholder="Confirm Password" 
                      name="password_confirmation" 
                      required 
                      autocomplete="new-password"
                    />
                  </div>
                </div>
              </div>

              <div class="row border-top border-secondary">
                <div class="col-12">
                  <div class="form-group">
                    <div class="pt-3">
                      <a href="{{ route('login') }}" class="btn btn-info">
                        <i class="mdi mdi-arrow-left fs-4 me-1"></i> Back to Login
                      </a>
                      <button class="btn btn-success float-end text-white" type="submit">
                        Reset Password
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      $(".preloader").fadeOut();
    </script>
  </body>
</html>