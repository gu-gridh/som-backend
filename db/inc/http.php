<?php

/** Extract a parameter from the query string. */
function get_param($name, $default = NULL) {
    return isset($_GET[$name]) ? $_GET[$name] : $default;
}

/** Output as JSON. */
function respond_json($data) {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    $json = json_encode($data);
    echo $json;
}

/** Respond with an error message. */
function respond_error($msg, $code = 500) {
    if ($code >= 500) {
        error_log("Responding $code: $msg");
    }
    http_response_code($code);
    respond_json(['error' => $msg]);
}
