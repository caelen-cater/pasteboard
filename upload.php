<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "file/";
    $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
    $randomFileName = generateRandomString(20) . $imageFileType;
    $target_file = $target_dir . $randomFileName;

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $fileUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/$target_file";
        $apiUrl = 'https://api.cirrus.center/api/v1/tools/upload/?file=' . urlencode($fileUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $cdnUrl = $data['url'];

        $redirectEntry = $randomFileName . '=' . $cdnUrl . "\n";
        file_put_contents($target_dir . 'redirect.txt', $redirectEntry, FILE_APPEND);

        unlink($target_file);

        $redirectUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/file/?id=$randomFileName";
        echo "$redirectUrl";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
