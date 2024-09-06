<?php
$n_key = isset($_POST['n_key']) ? $_POST['n_key'] : null;
$s_key = isset($_POST['s_key']) ? $_POST['s_key'] : null;

$parts = explode("|", $s_key);

// 要素数を確認
//echo count($parts);
if (count($parts) === 5) {
    // 4つの変数にそれぞれの値を割り当てる
    $iv_b64_enc = $parts[0];
    $key_b64_enc = $parts[1];
    $iv_token_b64_enc = $parts[2];
    $key_token_b64_enc = $parts[3];

    // 変数の内容を表示（必要に応じて削除または変更）
    echo "iv_b64_enc: " . $iv_b64_enc . "<br>";
    echo "key_b64_enc: " . $key_b64_enc . "<br>";
    echo "iv_token_b64_enc: " . $iv_token_b64_enc . "<br>";
    echo "key_token_b64_enc: " . $key_token_b64_enc . "<br>";

    echo decrypte_nkey($n_key, $iv_b64_enc, $key_b64_enc, $iv_token_b64_enc, $key_token_b64_enc);

} else {
    // エラーメッセージを表示して処理を中断
    echo "エラー: 4つの要素が与えられていません。";
    exit;
}
//echo $n_key;

function decrypte_nkey($enc_token, $iv_b64_enc, $key_b64_enc, $iv_token_b64_enc, $key_token_b64_enc) {
    $dec_token = openssl_decrypt(base64_decode($enc_token), 'aes-256-cbc', base64_decode($key_b64_enc), OPENSSL_RAW_DATA, base64_decode($iv_b64_enc));

    if ($dec_token === false) {
        return "第一段階の復号に失敗しました";
    }

    $token_decrypt = openssl_decrypt(base64_decode($dec_token), 'aes-256-cbc', base64_decode($key_token_b64_enc), OPENSSL_RAW_DATA, base64_decode($iv_token_b64_enc));

    if ($token_decrypt === false) {
        return "第二段階の復号に失敗しました";
    }

    return $token_decrypt;
}
//
?>