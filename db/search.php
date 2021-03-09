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

if (is_null($types)) {
    respond_error('Error finding types');
    exit;
}

// Add morpheme analysis if present.
if ($types) {
    $ids = implode(', ', array_map(function ($type) { return $type['lpnr']; }, $types));

    // Select all morphemes for all found types.
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

// Add type highlight info.
foreach ($types as &$type) {
    foreach (['gloss_item', 'en_trans'] as $col) {
        $start = strpos(strtolower($type[$col]), strtolower($s));
        if ($start > -1) {
            $type['highlight'] = [
                'key' => $col,
                'start' => $start,
                'end' => $start + strlen($s),
            ];
        }
    }
}

$morphemes = select("SELECT * from Morphemes
    WHERE REPLACE(Morpheme, '-', '') LIKE '$s%'
    OR gloss like '$s%'
    ORDER BY Morpheme");

if (is_null($morphemes)) {
    respond_error('Error finding morphemes');
    exit;
}

// Add morpheme highlight info.
foreach ($morphemes as &$morpheme) {
    foreach (['Morpheme', 'Gloss'] as $col) {
        $start = strpos(strtolower($morpheme[$col]), strtolower($s));
        if ($start > -1) {
            $morpheme['highlight'] = [
                'key' => $col,
                'start' => $start,
                'end' => $start + strlen($s),
            ];
        }
    }
}

respond_json([
    'types' => $types,
    'morphemes' => $morphemes,
]);
