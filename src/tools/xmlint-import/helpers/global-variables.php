<?php

/**
 * Global variables, KISS
 */

$service_name = "";
$service_dir = "";
$default_service = false;
$report_all_errors = false;
$xml_url = "";
$xml;
$pages_path = "";       // Absolute path shared by all the pages
$pages_url = [];        // Associative array ['page_url'] => number of references (int > 0);
$pages = [];            // Associative array ['page relative path under vdt'] => page content (string);
$service_config = "";
$global_config = "";

$old_allowed_services = "";
$new_allowed_services = "";
$old_default_service = "";
$new_default_service = "";

$default_controller_name = "";
$controllers = [];

$xml_ecran_conversion = [
    "affiche" => ["page", ["url" => ""]],
];
