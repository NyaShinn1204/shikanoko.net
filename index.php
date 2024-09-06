<?php
function generateRandomString($length = 10)
{
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <link rel="stylesheet" href="/assets/style/web-style.css?version=<?php echo generateRandomString(16) ?>">
</head>

<div id="main-container">

    ぬん？まだこのサイトは作り途中みたい... <br>
    しかせんべいでも食べてのんびり待ちましょう
    <div id="main-content">
        <div id="image-container">
            <img id="shika-image" src="https://shikanoko.net/assets/image/shika_face_nobg.png" alt="Shika Face">
        </div>
        <audio id="nunn-audio" src="https://shikanoko.net/assets/nunn_audio.mp3"></audio>
    </div>
    <div id="downside-content">
        <div id="realtime-mode">
            <div class="button_mode active">
                りあるたいむ同期
                <span class="active">ON</span>
                <span class>OFF</span>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-image: url("https://shikanoko.net/assets/image/shika_background.png");
        background-attachment: fixed;
        background-size: cover;
        background-position: center center;
    }

    /*
    #main-content {
    	width: 640px;
    	height: 460px;
    	background: rgba(0,0,0,0.3);
    	position: absolute;
    	top: 50%;
    	left: 50%;
    	margin-left: -320px;
    	margin-top: -230px;
    	border-radius: 10px;
    	text-align: center;
    }
    */
    #realtime-mode {
        bottom: 0;
        left: 50%;
        position: fixed;
        transform: translateX(-50%);
    }

    #realtime-mode .button_mode {
        text-align: center;
        display: inline-block;
        background: #dedede;
        color: #999;
        line-height: 1;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        width: 11em;
        font-size: 13px;

        /* おんどりゃーここでテキストコピー無効 */
        user-select: none;
        /* すべてのブラウザに対応するためのプレフィックスを含める */
        -webkit-user-select: none;
        /* Chrome, Safari, Edge */
        -moz-user-select: none;
        /* Firefox */
        -ms-user-select: none;
        /* Internet Explorer/Edge */
    }

    #realtime-mode .button_mode.active {
        background: #99cc00;
        color: #fff;
    }

    #realtime-mode .button_mode span.active {
        display: inline;
    }

    #realtime-mode .button_mode span {
        display: none;
    }

    #image-container {
        position: absolute;
        top: -100%;
        left: 50%;
        transform: translateX(-50%);
    }

    #shika-image {
        width: 100%;
        /*max-width: 300px; /* 最大サイズに設定 */
        cursor: pointer;
        position: relative;
        /* 追加 */
    }
</style>

<?php


// encrypte_lkey 処理
function gen_key()
{
    $numbers = [];
    for ($i = 0; $i < 4; $i++) {
        $numbers[] = rand(2, 13);
    }

    $key = implode(' * ', $numbers);
    return $key;
}

function gen_unix_enc()
{
    return gen_key();
}


// encrypte_nkey 処理
function encrypte_nkey()
{
    $iv_b64_enc = "yGird5xcnxmAi9qVvg7rDA==";
    $iv_token_b64_enc = "TyylczAOs9q4awHGrgQGKw==";
    $key = "Yt1BFir5c73iqmwieMSIhQ==";
    $key_token = "gC7u74wI2f6YBnycxjGzRQ==";

    $unix_time = time();

    $token_encrypt = base64_encode(openssl_encrypt(base64_encode($unix_time), 'aes-256-cbc', base64_decode($key_token), OPENSSL_RAW_DATA, base64_decode($iv_token_b64_enc)));

    $enc_token = openssl_encrypt($token_encrypt, 'aes-256-cbc', base64_decode($key), OPENSSL_RAW_DATA, base64_decode($iv_b64_enc));
    return base64_encode($enc_token);
}
?>

<script>
    var realtime_mode = 1;
    ion.sound({
        sounds: [{
            name: "nunn_audio"
        }],
        path: "/assets/",
        preload: true,
        multiplay: true
    });

    function encrypte_lkey() {
        Q = Math.round((new Date()).getTime() / 1000);
        P = Q * <?php echo gen_unix_enc(); ?>;
        B = btoa(P);
        B = B.replace("==", "")
        return B
    }

    function encrypte_nkey() {
        function generateRandomBase64(size) {
            const randomBytes = CryptoJS.lib.WordArray.random(size);
            return CryptoJS.enc.Base64.stringify(randomBytes);
        }

        // Base64エンコードされたキーとIVを生成
        const iv = generateRandomBase64(16); // 16 bytes for AES
        const key = generateRandomBase64(32); // 32 bytes for AES-256

        const iv_token = generateRandomBase64(16); // 16 bytes for AES
        const key_token = generateRandomBase64(32); // 32 bytes for AES-256

        const private_key = iv + "|" + key + "|" + iv_token + "|" + key_token + "|";

        const unix_time = Math.floor(Date.now() / 1000).toString();

        // Encrypt UNIX time with the token key and IV
        const token_encrypt = CryptoJS.AES.encrypt(unix_time, CryptoJS.enc.Base64.parse(key_token), {
            iv: CryptoJS.enc.Base64.parse(iv_token),
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }).toString();

        // Encrypt the resulting token with the main key and IV
        const enc_token = CryptoJS.AES.encrypt(token_encrypt, CryptoJS.enc.Base64.parse(key), {
            iv: CryptoJS.enc.Base64.parse(iv),
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }).toString();

        return [enc_token, private_key];
    }



    setInterval(function() {
        if (realtime_mode != 1) {
            return
        };
        //console.log(random_string(16))
        //console.log(Math.round((new Date()).getTime() / 1000))

        encrypt = encrypte_nkey()

        $.ajax({
            //url: 'get?'+Math.random(),
            url: 'get?' + encrypte_lkey(),
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
                nun: 'payload',
                n_key: encrypt[0],
                s_key: encrypt[1]
            }
        }).done(function(data) {
            if (localNynpass() < data.cnt) {
                after = data.cnt;
                before = localNynpass();
                renderNyanpass();
            }
        }).fail(function() {});
    }, 3000);

    $(document).ready(function() {
        // 画像を上から中央に移動
        $('#image-container').animate({
            top: '5%',
            transform: 'translate(-50%, -50%)'
        }, 1000, function() {
            // 移動完了後、クリック可能にする
            $('#shika-image').on('click', function() {
                // アニメーションが遅れないように、現在のアニメーションを完了させる
                $(this).finish().animate({
                        top: '-=15px'
                    }, 200)
                    .animate({
                        top: '+=15px'
                    }, 200);

                // オーディオを再生
                ion.sound.play("nunn_audio");
            });
        });
    });

    $('#realtime-mode .button_mode').on('click', function() {
        realtime_mode++;
        if (realtime_mode > 2) {
            realtime_mode = 1;
        }
        $('#realtime-mode .button_mode,#realtime-mode .button_mode span').toggleClass('active');
    });
</script>