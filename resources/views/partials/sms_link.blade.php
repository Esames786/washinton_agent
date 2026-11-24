{{--
    Click-to-text link for every "message" button (client, 23 Nov 2026: phones moved from
    RingCentral to Zoom; calls already open Zoom through tel:, texts still opened RingCentral).
    Zoom: zoomphonesms://+<E.164 number> (Zoom client 6.1+, a Zoom Phone number with SMS).
    CLICK_TO_TEXT_APP=ringcentral in .env brings back the old rcapp:// link.
--}}
<script>
    window.crmSmsUrl = function (number) {
        var raw = String(number == null ? '' : number).trim();
        if (@json(config('services.click_to_text.app', 'zoom')) === 'ringcentral') {
            return 'rcapp://r/sms?type=new&number=' + raw;
        }
        var digits = raw.replace(/\D/g, '');
        if (digits.length === 10) { digits = '1' + digits; }   // US number saved without the country code
        return 'zoomphonesms://+' + digits;
    };
</script>
