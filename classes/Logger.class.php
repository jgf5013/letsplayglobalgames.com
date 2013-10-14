<?php
class Logger {


    /* logging functions */
    public static function writeSQLError($errorno, $error, $query) {
        error_log( "\t" . date("Y-m-d H:i:s") . ">> ERROR " . $errorno . ": "
            . $error . " when executing query:" . $query . "\n", 3, "logs/err/err." . date('Ymd') . ".log" );
    }
    public static function writeError($function, $message) {
        error_log( "\t" . date("Y-m-d H:i:s") . ">> ERROR " . $function . ": "
            . $message . "\n", 3, "logs/err/err." . date('Ymd') . ".log" );
    }
    public static function writeLog() {
        if ( (func_num_args() === 1) && (func_get_arg(0) === "url") ) {
            error_log( date("Y-m-d H:i:s") . ">> " . UserSession::getCurrentURL() . "\n", 3, "logs/out/out." . date('Ymd') . ".log" );
        }
        else {
            $function = func_get_arg(0);
            $message = func_get_arg(1);
            error_log( "\t" . date("Y-m-d H:i:s") . ">> " . $function . ": "
                . $message . "\n", 3, "logs/out/out." . date('Ymd') . ".log" );
        }
    }
}