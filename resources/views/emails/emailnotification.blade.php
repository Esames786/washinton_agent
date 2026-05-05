<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Notification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

    {{-- Logo (optional) --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ url('frontend/img/logo/hello_transport.svg') }}" style="max-width: 150px;" alt="Washington Logo">
    </div>

    @if(!empty($image))
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ url('template_images/' . $image) }}" alt="Image" style="width: 100%; border-radius: 10px;">
        </div>
    @endif

    {{-- Personalized Body --}}
    <div style="color: #333333; font-size: 16px; line-height: 1.5;">
        {!! $personalized_body !!}
    </div>

    {{-- Footer --}}
    <div style="margin-top: 40px; font-size: 14px; color: #888888; text-align: center;">
        If you have any questions, just reply to this
        <a href="mailto:info@daydispatch.com" style="color: #113771;">info@shipa1.com</a>.<br />
        We're always happy to help out.<br /><br />
        <strong>Washington Team</strong>
    </div>
</div>
</body>
</html>
