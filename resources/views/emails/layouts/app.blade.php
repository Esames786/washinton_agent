<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px; background-color: #f9f9f9; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; border-radius: 10px; background-color: #fff;">
        @include('emails.includes.header')
        @yield('content')
        @include('emails.includes.footer')
    </div>
  </body>
</html>