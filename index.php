<?php
if (htmlspecialchars($_GET["date"])) {
    echo $_GET["date"];
}

header("HTTP/1.1 200 OK");
