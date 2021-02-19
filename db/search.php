<?php
require('inc/http.php');
require('inc/db.php');

$s = trim(get_param('s'));

if (!$s) {
    respond_error('Param `s` missing', 400);
    exit;
}

$rows = select("SELECT lpnr, gloss_item, en_trans FROM Types WHERE gloss_item LIKE '%$s%' OR en_trans LIKE '%$s%' LIMIT 100");

respond_json([
    'types' => $rows,
]);
