<?php
// only $_FILES["yourFileForm"] is need for this script
// 'daemon' is this process, don't forget to give him chown
/*
$upload_ok status
0 = upload failed
1 = upload successful
2 = not an image
3 = file already exists
4 = file too large
5 = invalid format
*/
function file_upload(&$_FUPLOAD,$target){
    $file_type = strtolower(pathinfo(basename($_FUPLOAD["name"]),PATHINFO_EXTENSION));
    $file_target = __DIR__ . $target . "." . $file_type;
    $upload_ok = "1";
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FUPLOAD["tmp_name"]);
    if($check !== false) {
        $upload_ok = "1";
    } else {
        $upload_ok = "2";
    }
    // Check if file already exists
    if (file_exists($file_target)) {
        $upload_ok = "3";
    }
    // Check file size
    if ($_FUPLOAD["size"] > 500000) {
        $upload_ok = "4";
    }
    // Allow certain file formats
    if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg"
    && $file_type != "gif" ) {
        $upload_ok = "5";
    }
    // Check if $upload_ok is not 0 (no condition conflicts)
    if ($upload_ok == "1" ) {
        if (move_uploaded_file($_FUPLOAD["tmp_name"], $file_target)) {
            $upload_ok = $target . "." . $file_type; //return file location
        } else {
            $upload_ok = "0";
        }
    }
    return $upload_ok;
}
?>
