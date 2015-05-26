<?php

require_once ('../application.init.php');

checkAppOpened();

jApp::loadConfig('soap/config.ini.php');
ini_set("soap.wsdl_cache_enabled", "0"); // disabling PHP's WSDL cache

$jelix = new jSoapCoordinator();
jApp::setCoord($jelix);
$jelix->request = new jSoapRequest();
$jelix->request->initService();
$jelix->processSoap();
