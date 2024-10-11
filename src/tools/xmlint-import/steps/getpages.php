<?php

/**
 * Gets the pages from Internet
 */

echo "Importing Pages fromn the Web...\n";

$nb_errors = 0;
foreach ($pages_url as $url => $nb) {
    echo "\n";
    echo "Page URL : " . $url . " , referenced " . $nb . " times.\n";

    if (substr($url, 0, strlen($pages_path)) !== $pages_path || strlen($pages_path) < 10) {
        echo "Not on the common path, will not be imported.\n";
        $nb_errors++;
    } else {
        $page = get_web_file($url);
        if ($page !== false) {
            $pages[$url] = $page;
            echo "Imported, " . strlen($page) . " bytes.\n";
            echo "Will be stored in services/" . $service_name . "/vdt/" . substr($url, strlen($pages_path)) . "\n";
        } else {
            echo "Error while fetching this page.\n";
            $nb_errors++;
        }
    }
}

echo "\n";
echo "Import ended, " . $nb_errors . " errors.\n";
YESno() || die("Aborted, no modification done.\n");
