<?php

/**
 * Display the purpose of this tool, asking to continue
 */

echo "xml-import.php is meant to import an existing and web available XMLint project into MiniPaviFwk.\n\n";
echo "It will do the following:\n";
echo "1 - Ask for your new Minitel Service name\n";
echo "2 - Ask for the XML url used on XMLint\n";
echo "3 - Import the XML, the pages and optionally display extended results\n";
echo "4 - Inform you on the proposed configuration, and ask if you want your new service as default service\n";
echo "5 - Create the subdirs, copy XML and Videotex, modify the configurations\n";
echo "6 - Display a summary of the work done and the errors encountered (unavailable pages)\n";

YESno() || die("Aborted, no modification done.\n");
