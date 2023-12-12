<?php

require_once 'includes/Minifier.php';
require_once 'includes/HunterObfuscator.php';

if (isset($_POST["buildFlux"])) {
    // Get data
    $buildName = $_POST["buildName"];
    $buildGate = $_POST["buildGate"];
    
    // Read
    $file = file_get_contents("builds/main.build");

    // Replace
    $file = str_replace("{buildGate}", $buildGate, $file);
    $file = str_replace("{buildName}", $buildName, $file);

    // Check for ..
    if (strpos($buildName, '.') !== false) {
        die("Permission denied >> .");
    }

    // If name is main.build
    if ($buildName == 'main.build') {
        die("Permission denied!");
    } 

    // Minificar o código JavaScript usando JShrink
    $minifiedCode = \JShrink\Minifier::minify($file, ['flaggedComments' => false]);

    // Obfuscar o código JavaScript usando Hunter PHP JavaScript Obfuscator
    $obfuscator = new HunterObfuscator($minifiedCode);
    $obfuscatedCode = $obfuscator->Obfuscate();

    // Escrever o arquivo obfuscado
    if (file_put_contents("builds/$buildName.js", $obfuscatedCode)) {
        die("Build created!");
    } else {
        die("Failed to create build");
    }
}
?>
