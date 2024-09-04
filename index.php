<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="msapplication-TitleColor" content="#E4E9F7">
    <meta name="title" content="しかのこのこのここしたんたん">
    <meta name="description" content="ぬん！">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://shikanoko.net">
    <meta property="og:title" content="しかのこ - ぬん！">
    <meta property="og:description" content="しかせんべいをたべよう！">
    <meta name="keywords" content="しかのこ,しかのこのこのここしたんたん,のこたん,ぬん,しかせんべい,鹿せんべい">

    <!-- のーまるあいこん -->
    <link rel="icon" href="/assets/favicon.ico">
    <!-- スマホ用アイコン -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.jpg">

    <title>ぬん！</title>

    <link rel="stylesheet" href="/assets/style/web-style.css?version=<?php echo generateRandomString(16) ?>">
</head>

ぬん？まだこのサイトは作り途中みたい... <br>
しかせんべいでも食べてのんびり待ちましょう