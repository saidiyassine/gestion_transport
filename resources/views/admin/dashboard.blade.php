@extends("dashboard.master")

@section("head")
@include("dashboard.layouts.head")
@endsection
@section("nav")
@include("dashboard.layouts.navbar")
@endsection
@section("aside")
@include("dashboard.layouts.aside")
@endsection

@section("content")
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Accueil</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                @isset($adminCount)
                <h3>{{ $adminCount }}</h3>
                @endisset
                <p>Employés</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                @isset($directeurCount)
                <h3>{{ $directeurCount }}</h3>
                @endisset
                <p>Transports</p>
              </div>
              <div class="icon">
                <i class="fas fa-bus"></i>
              </div>
            </div>
          </div>
        </div

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection


@section("footer")
@include("dashboard.layouts.footer")
@endsection
@section("footer-script")
@include("dashboard.layouts.footer-script")
@endsection
