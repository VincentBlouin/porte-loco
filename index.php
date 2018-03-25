<?php
require "predis/autoload.php";
Predis\Autoloader::register();
try {
    $redis = new Predis\Client();

    // This connection is for a remote server
    /*
        $redis = new PredisClient(array(
            "scheme" => "tcp",
            "host" => "153.202.124.2",
            "port" => 6379
        ));
    */
} catch (Exception $e) {
    header("HTTP/1.1 500 INTERNAL ERROR");
    die($e->getMessage());
}
if (isset($_GET["date"])) {
    $redis->rpush("doorDates", $_GET["date"]);
    header("HTTP/1.1 200 OK");
    die();
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>
<div id="content"></div>
<script>
    var dates = <?php echo json_encode($redis->lrange("doorDates", 0, -1))?>;
    var html = "";
    dates.forEach(function(date){
        html += date + "<br>"
    });
    document.getElementById("content").innerHTML = html
</script>
</body>
</html>
