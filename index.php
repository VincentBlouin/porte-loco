<?php
require "predis/autoload.php";
PredisAutoloader::register();
try {
    $redis = new PredisClient();

    // This connection is for a remote server
    /*
        $redis = new PredisClient(array(
            "scheme" => "tcp",
            "host" => "153.202.124.2",
            "port" => 6379
        ));
    */
    header("HTTP/1.1 200 OK");
} catch (Exception $e) {
    header("HTTP/1.1 500 INTERNAL ERROR");
    die($e->getMessage());
}
if (htmlspecialchars($_GET["date"])) {
    $redis->rpush("doorDates", $_GET["date"]);
} else {
    echo $redis->lpop("doorDates");
}
