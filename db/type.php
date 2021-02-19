<?php
require('inc/http.php');
require('inc/db.php');

$type_id = get_param('type');

if (!$type_id) {
    respond_error('Param missing: type', 400);
    exit;
}

$type = select_one("SELECT lpnr, gloss_item, en_trans FROM Types WHERE lpnr = $type_id");

$morphemes = select("SELECT Morphemes.Morpheme, Gloss, VowQual FROM MorphToType
    JOIN Types ON MorphToType.lpnrType = Types.lpnr
    JOIN Morphemes ON MorphToType.Morpheme = Morphemes.Morpheme
    WHERE lpnrType = $type_id");

$tokens = select("SELECT * FROM Tokens WHERE data_item = '{$type['gloss_item']}'");

respond_json($type + [
    'morphemes' => $morphemes,
    'tokens' => $tokens,
]);
