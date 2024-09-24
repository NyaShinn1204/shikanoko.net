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
    <meta name="description" content="ぬん！の気持ちを全世界にシェアできるサービス">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://shikanoko.net">
    <meta property="og:title" content="ぬんぬんボタン">
    <meta property="og:description" content="しかせんべいをたべよう！">
    <meta name="keywords" content="しかのこ,しかのこのこのここしたんたん,のこたん,ぬん,しかせんべい,鹿せんべい">

    <!-- のーまるあいこん -->
    <link rel="icon" href="/assets/favicon.ico">
    <!-- スマホ用アイコン -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.jpg">

    <title>ぬんぬんボタン</title>

    <script type="text/javascript" src="/assets/js/lib/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/lib/ion.sound.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <link rel="stylesheet" href="/assets/style/web-style.css?version=<?php echo generateRandomString(16) ?>">
</head>

<div id="main-container">
    <div id="main-content">
        <div id="image-container">
            <img oncontextmenu='return false;' oncopy='return false;' id="shika-image" src="https://shikanoko.net/assets/image/shika_face_nobg.png" alt="Shika Face">
        </div>
        <audio id="nunn-audio" src="https://shikanoko.net/assets/nunn_audio.mp3"></audio>
    </div>
    <div id="downside-content">
        <span>とーたるしかせんべい</span>
        <div id="total"></div>
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
    @font-face {
        font-family: Bizin-Gothic;
        src: url(/assets/fonts/BizinGothic-Bold.ttf);
    }

    body {
        background-image: url("https://shikanoko.net/assets/image/shika_background_zipped.jpg");
        background-attachment: fixed;
        background-size: cover;
        background-position: center center;
    }

    #downside-content {
        text-align: center;
        bottom: 0;
        left: 50%;
        position: fixed;
        transform: translateX(-50%);
    }

    #total {
        font-size: 70px;
        text-align: center;
        line-height: 1;
        color: #e6bc99;
        margin-top: 10px;
        font-family: Bizin-Gothic;
        filter: dropshadow(color=#fff, offX=0, offY=-1) dropshadow(color=#fff, offX=1, offY=0) dropshadow(color=#fff, offX=0, offY=1) dropshadow(color=#fff, offX=-1, offY=0);
        text-shadow: #fff 1px 1px 0px, #fff -1px 1px 0px, #fff 1px -1px 0px, #fff -1px -1px 0px;
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
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
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
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
    }

    #shika-image {
        width: 100%;
        cursor: pointer;
    }

    @media (min-width: 769px) {
        /* ぱそこんばん */
        #image-container {
            top: 30%;
        }
    }

    @media (max-width: 768px) {
        /* すまほばん */
        #image-container {
            top: 50%;
        }
    }



    #shika-image {
        width: 100%;
        cursor: pointer;
        position: relative;
    }
</style>

<?php


// encrypte_lkey 処理
function gen_unix_enc()
{
    $numbers = [];
    for ($i = 0; $i < 4; $i++) {
        $numbers[] = rand(2, 13);
    }

    $key = implode(' * ', $numbers);
    return $key;
}
?>

<script>
    /*
    ぬん！ここには処理を書いてるよ～
    n_keyはunixtimeが+-3いないじゃないとはじかれるよ～
    
    もしカウントを取得したいなら
    addのapiで帰ってくる値から-1したほうがいいよ～
    */

    var realtime_mode = 1;
    var time_cnt = 0;
    var max_time_cnt = 200;
    var deley = 10;

    ion.sound({
        sounds: [{
            name: "nunn_audio"
        }],
        path: "/assets/",
        preload: true,
        multiplay: true
    });

    function init_nunn() {
        encrypt = encrypte_nkey()
        $.ajax({
            url: 'get?' + encrypte_lkey(),
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
                nun: 'payload',
                n_key: encrypt[0],
                s_key: encrypt[1]
            }
        }).done(function(response) {
            update_total(response.data.view_count);
        }).fail(function() {});
    }

    function get_local_nun() {
        return parseInt($("#total").html(), 10) || 0;
    }

    function render_nunn() {
        time_cnt++;
        if (time_cnt <= max_time_cnt) {
            var cnt = Math.floor((after - before) * time_cnt / max_time_cnt) + before;
            update_total(cnt);
            timer = setTimeout(function() {
                render_nunn()
            }, deley);
        } else {
            clearTimeout(timer);
            timer = null;
            time_cnt = 0;
        }
    }

    function update_total(cnt) {
        if (get_local_nun() > cnt) {
            cnt = get_local_nun();
        }
        $('#total').html(cnt);
    }

    function add_nunn() {
        $.ajax({
            url: 'add',
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
                shika: 'noko'
            }
        }).done(function(data) {
            if (realtime_mode == 2) {
                update_total(data.view_count);
            }
        }).fail(function() {});
        if (realtime_mode == 1) {
            var add = get_local_nun() + 1;
            update_total(add);
        } else {
            var add = get_local_nun() + 1;
            update_total(add);
        }
    }

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

        // いろいろ生成
        const iv = generateRandomBase64(16);
        const key = generateRandomBase64(32);

        const iv_token = generateRandomBase64(16);
        const key_token = generateRandomBase64(32);

        const private_key = iv + "|" + key + "|" + iv_token + "|" + key_token + "|";

        const unix_time = Math.floor(Date.now() / 1000).toString();

        // 第一段階暗号化
        const token_encrypt = CryptoJS.AES.encrypt(unix_time, CryptoJS.enc.Base64.parse(key_token), {
            iv: CryptoJS.enc.Base64.parse(iv_token),
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }).toString();

        // 第二段階暗号化
        const enc_token = CryptoJS.AES.encrypt(token_encrypt, CryptoJS.enc.Base64.parse(key), {
            iv: CryptoJS.enc.Base64.parse(iv),
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }).toString();

        return [enc_token, private_key];
    }

    $(document).ready(function() {
        // ふぁーすとろーど
        init_nunn();

        // りあるたいむ 機能
        setInterval(function() {
            if (realtime_mode != 1) {
                return;
            }

            let encrypt = encrypte_nkey();

            $.ajax({
                url: 'get?' + encrypte_lkey(),
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {
                    nun: 'payload',
                    n_key: encrypt[0],
                    s_key: encrypt[1]
                }
            }).done(function(response) {
                if (get_local_nun() < response.data.view_count) {
                    after = response.data.view_count;
                    before = get_local_nun();
                    render_nunn();
                }
            }).fail(function() {
                console.error('Failed to retrieve data.');
            });
        }, 3000);

        // 画像を上からこんにちは！
        $('#image-container').animate({
            top: $(window).width() > 768 ? '30%' : '50%',
            transform: 'translate(-50%, -50%)'
        }, 1000, function() {
            $('#shika-image').on('click', function() {
                $(this).finish().animate({
                    top: '-=15px'
                }, 200).animate({
                    top: '+=15px'
                }, 200);

                ion.sound.play("nunn_audio");
                add_nunn();
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