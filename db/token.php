<?php
require('inc/http.php');
require('inc/db.php');

$token_id = get_param('token');

if (!$token_id) {
    respond_error('Param missing: token', 400);
    exit;
}

$token = select_one("SELECT lpnr, clip_file, data_item, som_tone, Vowel_quality FROM Tokens WHERE lpnr = $token_id");

if (!$token) {
    respond_error("No token with id $token_id", 404);
    exit;
}

$type = select_one("SELECT lpnr, gloss_item, en_trans FROM Types WHERE gloss_item = '{$token['data_item']}'");

respond_json([
    'token' => $token,
    'type' => $type,
]);
