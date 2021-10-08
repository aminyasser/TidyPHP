<!DOCTYPE html>
<html>

<head>
    <link type="text/css" rel="stylesheet" href="<?= asset('css/404.css') ?>" />
</head>

<body class="permission_denied">
    <div id="tsparticles"></div>
    <div class="denied__wrapper">
        <h1>404</h1>
        <h3>LOST IN <span>SPACE</span> <?= env('APP_NAME') ?> ? Hmm, looks like that page doesn't exist.</h3>
        <img id="astronaut" src="<?= asset('images/astronaut.svg')?>" />
        <img id="planet" src="<?= asset('images/planet.svg')?>" />
    </div>

    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/tsparticles@1.18.11/dist/tsparticles.min.js"></script>
    <script type="text/javascript" src="<?= asset('css/404.css') ?>"></script>

</html>