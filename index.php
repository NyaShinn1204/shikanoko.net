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

    <script type="text/javascript" src="/assets/js/lib/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/lib/ion.sound.min.js"></script>

    <link rel="stylesheet" href="/assets/style/web-style.css?version=<?php echo generateRandomString(16) ?>">
</head>

<div id="main-content">
    
    ぬん？まだこのサイトは作り途中みたい... <br>
    しかせんべいでも食べてのんびり待ちましょう
    <div id="image-container">
        <img id="shika-image" src="https://shikanoko.net/assets/image/shika_face.png" alt="Shika Face">
    </div>
    <audio id="nunn-audio" src="https://shikanoko.net/assets/nunn_audio.mp3"></audio>
</div>

<style>
body {
    background-image: url("https://shikanoko.net/assets/image/shika_background.png");
    background-attachment: fixed;
    background-size: cover;
    background-position: center center;
}
#image-container {
    position: absolute;
    top: -100%;
    left: 50%;
    transform: translateX(-50%);
}

#shika-image {
    width: 100%;
    max-width: 300px; /* 最大サイズに設定 */
    cursor: pointer;
    position: relative; /* 追加 */
}
</style>

<script>
ion.sound({
	sounds: [{name: "nunn_audio"}],
	path: "/assets/",
	preload: true,
	multiplay: true
});

$(document).ready(function(){
    // 画像を上から中央に移動
    $('#image-container').animate({
        top: '50%',
        transform: 'translate(-50%, -50%)'
    }, 1000, function() {
        // 移動完了後、クリック可能にする
        $('#shika-image').on('click', function() {
            // yを-15pxし、戻す
            $(this).animate({ top: '-=15px' }, 200)
                   .animate({ top: '+=15px' }, 200);
            
            // オーディオを再生
            //$('#nunn-audio')[0].play();
            ion.sound.play("nunn_audio");
        });
    });
});

</script>