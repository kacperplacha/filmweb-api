<?php

$login = '';
$password = '';

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once '../src/nSolutions/Filmweb.php';

$filmweb = \nSolutions\Filmweb::instance();

\nSolutions\Request::$default_options += array(
    CURLOPT_COOKIEJAR => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookies.txt',
    CURLOPT_COOKIEFILE => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookies.txt'
);

$user = $filmweb->Login($login, $password)->execute();
$votes = $filmweb->getUserFilmVotes($user->user_id, 0)->execute();

$data = [];
foreach ($votes as $vote) {
    $filmInfo = $filmweb->getFilmInfoFull($vote->filmId)->execute();
    $data[] = [
        $vote->filmId,
        $filmInfo->title,
        $filmInfo->originalTitle,
        $vote->date,
        $vote->vote,
        $vote->fav,
        $vote->comment,
        $vote->type,
    ];
}

file_put_contents('db.json', json_encode($data));

echo 'Done';
