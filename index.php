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
<?php
echo "résultats\n";
//echo ($redis->exists('doorDates')) ? "Oui" : "please populate the message key";
//print_r($redis->lpop);
//$redis->rpush("aList", "poire");
print_r(
    $redis->lpop("doorDates")
);
//print_r(
//    $redis->lpop("aList")
//);
//$redis->set('testMessage', 'Hello world');
//print_r(
//    $redis->keys("*")
//);

//echo $redis->get("testMessage")

//echo "fin résultats"
?>
</body>
</html>
