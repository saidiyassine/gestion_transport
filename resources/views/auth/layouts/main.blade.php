<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="/" class="logo"><img src="{{asset("images/betycor.png")}}" width="170px" style="border-radius: 50%"></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Connectez-vous pour commencer votre session.</p>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
       @endif
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
       @endif
        <form action="{{ url("/auth") }}" method="post">
          @csrf
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <p class="mb-0">
            <a href="{{url('registerForm')}}" class="text-center">Enregistrer un nouveau compte</a>
          </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->
</body>
