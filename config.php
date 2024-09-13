<?php

// Configuration globale

// The entry-point URL
const URL = "http://144.217.165.236:8000/index.php";

// The URL for XML local Videotex Pages, replaced by vdt/{pagename} when interpreting XML
// Elsewhere pages will be fetched through a http/https query to keep compatiblity with existing XML (TEST only)
const XML_PAGES_URL = "https://minitelbidouille.pvigier.com/pages/";
// const XML_PAGES_URL = false;  // Doesn't try to use pages from vdt folder when a http/https scheme is used

// Set to true to enable Debug informations on-screen, and DEBUG actions through displayed PIN.
const DEBUG = false;

// The default controller & Sommaire controller
const DEFAULT_CONTROLLER = false;  // If false, use xml/{DEFAULT_XML_FILE}.xml
// const DEFAULT_XML_FILE = 'macbidouille';  // If starting from XML, use xml/{this file}.xml
const DEFAULT_XML_FILE = 'demo';  // If starting from XML, use xml/{this file}.xml
