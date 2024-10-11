<?php

/**
 * Asks for the Service Name, AaZz09-
 */

while (true) {
    echo "Your Minitel Service Name (AaZz09-): ";
    $service_name = trim(readline());
    echo "\n";
    if (!empty($service_name)) {
        $filtered_service_name = preg_replace('/[^A-Za-z0-9\-]/', '', $service_name);
        if ($filtered_service_name === $service_name) {
            break;
        }
        echo "'$service_name' is not valid. Only AaZz09- are allowed.\n\n";
    } else {
        echo "Please enter a Minitel Service Name\n";
    }
}
echo "You service name is : $service_name\n\n";

if (file_exists("./services/$service_name")) {
    die("This service already exists : $service_name\n");
}
