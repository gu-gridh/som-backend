<?php
require('inc/http.php');
require('inc/db.php');

$morpheme = get_param('morpheme');

if (!$morpheme) {
    respond_error('Param missing: morpheme', 400);
    exit;
}

$morpheme_row = select_one("SELECT Morpheme, Gloss, VowQual FROM Morphemes WHERE Morpheme = '$morpheme'");

$types = select("SELECT Types.lpnr, gloss_item, en_trans
    FROM Types
    JOIN MorphToType ON MorphToType.lpnrType = Types.lpnr
    WHERE Morpheme = '$morpheme'
    GROUP BY Types.lpnr");

foreach ($types as &$type) {
    $data_item = mysqli_real_escape_string($db, $type['gloss_item']);
    $type['tokens'] = select("SELECT * FROM Tokens WHERE data_item = '$data_item'");
}

respond_json([
    'morpheme' => $morpheme_row,
    'types' => $types,
]);
