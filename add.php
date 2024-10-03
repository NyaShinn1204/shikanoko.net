<?php
$connect = mysqli_connect("192.168.40.110", "update_only", "ujQZG)GUeTu4Dyz8", "shikanoko");
mysqli_set_charset($connect, 'utf8');

if ($connect === false) {
    die("Oops! Unable to connect: " . mysqli_connect_error());
}

// Check if the POST request contains 'shika' key with value 'data'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shika']) && $_POST['shika'] === 'noko') {
    // Update the view_count
    $sql_update = "UPDATE view SET view_count = view_count + 1 WHERE id = 1";
    if (mysqli_query($connect, $sql_update)) {
        // Fetch the updated view_count
        $sql_select = "SELECT view_count FROM view WHERE id = 1";
        if ($result = mysqli_query($connect, $sql_select)) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $view_count = $row['view_count'];

                $data = array(
                    'result' => 'success',
                    'message' => array('jp_msg' => '更新の成功', 'en_msg' => 'Success update'),
                    'data' => array('view_count' => $view_count)
                );

                // Convert data to JSON
                $jsonData = json_encode($data);

                // Set the Content-Type header
                header('Content-Type: application/json');

                // Output the JSON string
                echo $jsonData;
            }
            mysqli_free_result($result);
        }
    } else {
        $data = array(
            'result' => 'failed',
            'message' => array('jp_msg' => '更新の失敗', 'en_msg' => 'Failed update')
        );

        // Convert data to JSON
        $jsonData = json_encode($data);

        // Set the Content-Type header
        header('Content-Type: application/json');

        // Output the JSON string
        echo $jsonData;
    }
} else {
    // Bad request response
    $data = array(
        'result' => 'failed',
        'message' => array('jp_msg' => 'バッドリクエスト', 'en_msg' => 'Bad request')
    );

    // Convert data to JSON
    $jsonData = json_encode($data);

    // Set the Content-Type header
    header('Content-Type: application/json');

    // Output the JSON string
    echo $jsonData;
}

mysqli_close($connect);
?>
