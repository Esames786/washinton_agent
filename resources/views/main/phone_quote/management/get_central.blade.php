<?php

if (isset($_GET['num_vehicles'])) {

    $num_vehicles = $_GET['num_vehicles'];
    $ozip = $_GET['ozip'];
    $dzip = $_GET['dzip'];
    $enclosed = $_GET['enclosed'];
    $inop = $_GET['inop'];
    $vehicle_types = $_GET['vehicle_types'];
    $miles = $_GET['miles'];


    if (!empty($gets)) {

        $cachee = $gets->cachee;
        $get_ses = $gets->get_ses;

    }


    $url = "https://www.centraldispatch.com/protected/cargo/sample-prices-lightbox?num_vehicles=$num_vehicles&ozip=$ozip&dzip=$dzip&enclosed=$enclosed&inop=$inop&vehicle_types=$vehicle_types&miles=$miles&$get_ses";
    $cookie = "Cookie: $cachee";
    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "Accept-language: en\r\n" .
                "$cookie"
        )
    );

    $context = stream_context_create($opts);

    $data = file_get_contents($url, false, $context);
    $cacheFile = 'cache.html';

    ob_start();
    // write content

    $content = ob_get_contents();
    ob_end_clean();
    file_put_contents($cacheFile, $content);
    echo $content;

} else {

}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Central Price</title>
    <style>
        .login-page.app-page > .row {
    justify-content: center;
}

a.central-color {
    color: #0056b3;
    padding: 0;
    border-bottom: 1px solid;
}

.container.footer.navbar-bottom {
    background: #ddd;
    padding: 10px;
    margin: 1rem auto;
}
    </style>
</head>
<body>

@if(!empty($data))


    <?php echo $data ?>

@endif

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>
</html>
