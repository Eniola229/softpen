<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="SchoolCode for online pratice exam" />
    <meta name="description" content="SchoolCode for online pratice exam" />
    <meta name="robots" content="noindex,nofollow" />
    <title>SchoolCode Africa - Register</title>
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
          <div id="registerform">
            <div class="text-center pt-3 pb-3">
              <span class="db"><h1 style="color: white;">SchoolCode Africa</h1></span>
              <h3 style="color: white;">Registration</h3>
            </div>

            <form class="form-horizontal mt-3" action="{{ route('register') }}" method="POST">
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

              <div class="row pb-4">
                <div class="col-12">
                  <!-- Name -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-info text-white h-100" id="basic-addon1">
                        <i class="mdi mdi-account fs-4"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg" placeholder="Full Name" 
                           name="name" value="{{ old('name') }}" required />
                  </div>

                  <!-- Email -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-success text-white h-100" id="basic-addon2">
                        <i class="mdi mdi-email fs-4"></i>
                      </span>
                    </div>
                    <input type="email" class="form-control form-control-lg" placeholder="Email" 
                           name="email" value="{{ old('email') }}" required />
                  </div>

                  <!-- Class -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-primary text-white h-100" id="basic-addon3">
                        <i class="mdi mdi-school fs-4"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg" placeholder="Class (e.g., SS3)" 
                           name="class" value="{{ old('class') }}" required />
                  </div>

                  <!-- Age -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-secondary text-white h-100" id="basic-addon4">
                        <i class="mdi mdi-calendar fs-4"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg" placeholder="Age" 
                           name="age" value="{{ old('age') }}" required />
                  </div>

                  <!-- School -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-dark text-white h-100" id="basic-addon5">
                        <i class="mdi mdi-home-modern fs-4"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg" placeholder="School Name" 
                           name="school" value="{{ old('school') }}" required />
                  </div>

                  <!-- Department (Optional) -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-info text-white h-100" id="basic-addon6">
                        <i class="mdi mdi-book-open-variant fs-4"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg" placeholder="Department (Optional)" 
                           name="department" value="{{ old('department') }}" />
                  </div>

                  <!-- Password -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-warning text-white h-100" id="basic-addon7">
                        <i class="mdi mdi-lock fs-4"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg" placeholder="Password" 
                           name="password" required />
                  </div>

                  <!-- Confirm Password -->
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-danger text-white h-100" id="basic-addon8">
                        <i class="mdi mdi-lock-check fs-4"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg" placeholder="Confirm Password" 
                           name="password_confirmation" required />
                  </div>
                </div>
              </div>

              <div class="row border-top border-secondary">
                <div class="col-12">
                  <div class="form-group">
                    <div class="pt-3">
                      <a href="{{ route('login') }}" class="btn btn-info">
                        <i class="mdi mdi-login fs-4 me-1"></i> Already have an account?
                      </a>
                      <button class="btn btn-success float-end text-white" type="submit">
                        Register
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