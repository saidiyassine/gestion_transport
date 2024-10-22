<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- ===== CSS ===== -->
      @yield("head")
        <title>Betycor</title>
    </head>
    <body>
        <div>
            @yield("main")
        </div>
    </body>
    @yield("footer-script")
</html>
