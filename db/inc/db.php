<?php

$config = parse_ini_file(__DIR__ . '/../../config.ini') + [
    // Defaults.
    'DB_USER' => 'som',
    'DB_DATABASE' => 'som',
];

$db = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PASS'], $config['DB_DATABASE']);

if ($db->connect_errno) {
    error_log("Failed to connect to MySQL: " . $db->connect_error);
}

$db->set_charset("utf8");

/** Perform a select query and return results as a generator. */
function select($query) {
    global $db;
    $res = $db->query($query);
    if ($db->errno) {
        error_log($db->error);
        return FALSE;
    }
    return $res->fetch_all(MYSQLI_ASSOC);
}

/** Perform a select query and return the only (or first) result. */
function select_one($query) {
    global $db;
    $res = $db->query($query);
    if ($db->errno) {
        error_log($db->error);
        return FALSE;
    }
    return $res->fetch_assoc();
}
