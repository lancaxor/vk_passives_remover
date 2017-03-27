<?php
/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 27.03.2017
 * Time: 21:44
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/VkAuthorizer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Storage.php';

// error from VK
if(isset($_GET['error_description'])) {
    die('Get code error: ' . $_GET['error_description']);
}

// data from client
if(isset($_GET['is_ajax']) && $_GET['is_ajax'] && !empty($_GET['access_token'])) {

    $token = $_GET['access_token'];
    $vk = new VkAuthorizer();
    $vk->setToken($token);
    $storage = new Storage();
    $storage->loadData()->setData('token', $token)->saveData();
    $jsonResponse = [
        'redirect' => 'http://' . $_SERVER['HTTP_HOST']
    ];
    die(json_encode($jsonResponse));
}
?>
<html>
<head><title>Redirecting...</title>
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            var hash = window.location.hash;
            var authUrl = '<?php global $config; echo $config['vk_auth_redirect']?>';
            hash = hash.substr(1);  // remove '#' sign
            var startIndex = hash.indexOf('access_token=');
            startIndex += 'access_token='.length;
            var endAmpIndex = hash.indexOf('&', startIndex);
            var endIndex = endAmpIndex < 0 ? hash.length : endAmpIndex;
            var accessToken = hash.substr(startIndex, endIndex - startIndex);
            $.ajax({
                url: authUrl,
                data: {
                    access_token: accessToken,
                    is_ajax: 1
                }
            }).done(function(response) {
                var data = JSON.parse(response);
                var redirect = data.redirect;
                if(redirect !== undefined) {
                    window.location.replace(redirect);
                }
            });
        });
    </script>
</head>
<body>Redirecting...</body>
</html>
