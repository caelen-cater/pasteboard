<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apikey = 'YOUR_API_KEY_HERE';
    $apiUrl = 'https://us-east.cirrusapi.com/v2/data/upload/';

    $file = $_FILES["fileToUpload"];
    $filePath = $file["tmp_name"];
    $fileName = $file["name"];

    $cfile = new CURLFile($filePath, $file["type"], $fileName);

    $postFields = array('file' => $cfile);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer $apikey"
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['url'])) {
        echo $data['url'];
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
