<!DOCTYPE html>
<html lang="en">

    @yield("head")

<body>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

         @yield("nav")
         @yield("aside")

          <!-- Content Wrapper. Contains page content -->
          @yield("content")

          @yield("footer")

        </div>
        <!-- ./wrapper -->
        @yield("footer-script")
        </body>

</body>
</html>
