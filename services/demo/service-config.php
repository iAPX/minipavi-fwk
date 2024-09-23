<?php

/**
 * Configuration for the Demo service
 */

// The URL for XML local Videotex Pages, replaced by services/{serviceName}/vdt/{pagename} when interpreting XML
// Elsewhere pages will be fetched through a http/https query to keep compatiblity with existing XML (TEST only)
// const XML_PAGES_URL = "https://minitelbidouille.pvigier.com/pages/";
const XML_PAGES_URL = false;  // Don't try to use pages from vdt folder when a http/https scheme is present

// The default controller & Sommaire controller
// For a service beginning with a VideotexController or XmlController, put it here
const DEFAULT_CONTROLLER = false;  // If false, use xml/{DEFAULT_XML_FILE}.xml
const DEFAULT_XML_FILE = 'demo';  // If starting from XML, use xml/{this file}.xml