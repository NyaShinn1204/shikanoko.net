<?php
$connect = mysqli_connect("192.168.40.110", "update_only", "ujQZG)GUeTu4Dyz8", "shikanoko");
mysqli_set_charset($connect, 'utf8');

if ($connect === false) {
    die("Opps Unable to connect " . mysqli_connect_error());
}

$n_key = isset($_POST['n_key']) ? $_POST['n_key'] : null;
$s_key = isset($_POST['s_key']) ? $_POST['s_key'] : null;

$parts = explode("|", $s_key);

function check_nowunixtime($unixtime) {
    $current_time = time();
    $difference = abs($current_time - $unixtime);
    
    return $difference <= 3;
}

// 要素数を確認
if (count($parts) === 5) {
    // 4つの変数にそれぞれの値を割り当てる
    $iv_b64_enc = $parts[0];
    $key_b64_enc = $parts[1];
    $iv_token_b64_enc = $parts[2];
    $key_token_b64_enc = $parts[3];

    $decrypt_nkey = decrypte_nkey($n_key, $iv_b64_enc, $key_b64_enc, $iv_token_b64_enc, $key_token_b64_enc);
    
    if (check_nowunixtime($decrypt_nkey)) {
        // view_countを取得するためのSQLクエリ
        $sql_view_count = "SELECT view_count FROM view WHERE id = 1";
        $result = mysqli_query($connect, $sql_view_count);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $view_count = $row['view_count'];

            $data = array(
                'result' => 'success',
                'message' => array('jp_msg' => '正しいnkeyです', 'en_msg' => 'Correct nkey'),
                'data' => array('view_count' => $view_count)
            );
        } else {
            $data = array(
                'result' => 'failed',
                'message' => array('jp_msg' => 'view_countの取得に失敗しました', 'en_msg' => 'Failed to retrieve view_count')
            );
        }

        mysqli_free_result($result);

    } else {
        $data = array(
            'result' => 'failed',
            'message' => array('jp_msg' => '不正なnkeyです', 'en_msg' => 'Incorrect nkey')
        );
    }
} else {
    $data = array(
        'result' => 'failed',
        'message' => array('jp_msg' => '4つの要素が与えられていません', 'en_msg' => 'Not all 4 elements are provided')
    );
}

// JSONに変換して返す
$jsonData = json_encode($data);
header('Content-Type: application/json');
echo $jsonData;

mysqli_close($connect);

function decrypte_nkey($enc_token, $iv_b64_enc, $key_b64_enc, $iv_token_b64_enc, $key_token_b64_enc) {
    // Decode the incoming base64-encoded token first
    $enc_token_decoded = base64_decode($enc_token);
    
    // First stage decryption: Decrypt the outermost layer
    $dec_token = openssl_decrypt($enc_token_decoded, 'aes-256-cbc', base64_decode($key_b64_enc), OPENSSL_RAW_DATA, base64_decode($iv_b64_enc));

    if ($dec_token === false) {
        return "第一段階の復号に失敗しました";
    }

    // The result of first decryption needs to be base64 decoded before next decryption
    $token_decrypt_base64 = base64_decode($dec_token);

    // Second stage decryption: Decrypt the innermost layer
    $token_decrypt = openssl_decrypt($token_decrypt_base64, 'aes-256-cbc', base64_decode($key_token_b64_enc), OPENSSL_RAW_DATA, base64_decode($iv_token_b64_enc));

    if ($token_decrypt === false) {
        return "第二段階の復号に失敗しました";
    }

    return $token_decrypt;
}
?>
