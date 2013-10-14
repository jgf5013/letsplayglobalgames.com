<?php
function __autoload($className) {
    include 'classes/' . $className . '.class.php';
}
function reportError($errno, $errstr, $errfile, $errline)
{  
    $localLogger = new Logger();
    $localLogger->writeError('common', 'ERROR ' . $errno . ' on line ' . $errline . ' of ' . $errfile . ': '. $errstr);
    header('Location: /error', true, 302);
    die();
}


ob_start();
session_start();
set_error_handler('reportError');
date_default_timezone_set("America/New_York");


/* only instantiate the global variables once per session */
if (! isset($_SESSION['userSession']) ) {

    $_SESSION['userSession'] = new UserSession();    
}
else {
    // webserver not letting me keep the database connections on page reload so you need to recall setDatabaseConnections
    $_SESSION['userSession']->dataAccessor->setDatabaseConnections();
    $_SESSION['userSession']->logger->writeLog('url');
    $_SESSION['userSession']->logger->writeLog('common', 'userSession already created');
}

/* if the user just tried to log in */
if ( (isset($_POST['userName']) && isset($_POST['password'])) || isset($_POST['logout_submit']) ) {
    
    $_SESSION['userSession']->logger->writeLog('common', 'setting user to ' . $_SESSION['userSession']->getUser());
    $_SESSION['userSession']->setUser();
    $_SESSION['userSession']->logger->writeLog('common', 'user is now set to ' . $_SESSION['userSession']->getUser());
}

if ( isset($_POST['user_input']) ) {

    $_SESSION['userSession']->navigator->submitInput($_POST['user_input']);
}
?>