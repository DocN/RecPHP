<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
include "../dbCredentials.php";
makedir("u");
$dir = "u/" . uniqid();
makeDir($dir);
$target_dir =   $dir . "/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$message['message'] = '';
$domain = $displayURL . "bcitrec/instructor/" . $dir . '/' . $_FILES["file"]["name"];
$message['url'] = $domain;
$message['result'] = false;

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        $message['message'] = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $message['message'] = "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $message['message'] =  "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["file"]["size"] > 5000000) {
    $message['message'] =  "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $message['message'] =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $message['message'] =  "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $message['message'] =  "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
        $message['result'] = true;
    } else {
        $message['message'] =  "Sorry, there was an error uploading your file.";
    }
}
echo json_encode($message);

function makeDir($path)
{
     return is_dir($path) || mkdir($path);
}
?>