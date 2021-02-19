<?php
require('inc/http.php');
require('inc/db.php');

$morpheme = get_param('morpheme');

if (!$morpheme) {
    respond_error('Param missing: morpheme', 400);
    exit;
}

$types = select("SELECT Types.lpnr, gloss_item, en_trans
    FROM Types
    JOIN MorphToType ON MorphToType.lpnrType = Types.lpnr
    WHERE Morpheme = '$morpheme'");

foreach ($types as &$type) {
    $type['tokens'] = select("SELECT * FROM Tokens WHERE data_item = '{$type['gloss_item']}'");
}

respond_json([
    'types' => $types,
]);
