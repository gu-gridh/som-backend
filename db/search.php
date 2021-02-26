<?php
require('inc/http.php');
require('inc/db.php');

$s = trim(get_param('s'));

if (!$s) {
    respond_error('Param missing: s', 400);
    exit;
}

$types = select("SELECT lpnr, gloss_item, en_trans FROM Types
    WHERE gloss_item LIKE '%$s%'
    OR en_trans LIKE '%$s%'
    ORDER BY gloss_item
    LIMIT 100");


if ($types) {
    $ids = implode(', ', array_map(function ($type) { return $type['lpnr']; }, $types));

    $morphemes = select("SELECT lpnrType, Morpheme
        FROM MorphToType
        WHERE lpnrType IN ($ids)
        ORDER BY lpnr");

    foreach ($types as &$type) {
        foreach ($morphemes as $morpheme) {
            if ($morpheme['lpnrType'] == $type['lpnr']) {
                $type['morphemes'][] = $morpheme['Morpheme'];
            }
        }
    }
}

respond_json([
    'types' => $types,
]);
