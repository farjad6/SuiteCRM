<?php
$upload_dir = 'upload://';
if( isset( $_REQUEST['file'] ) && isset( $_REQUEST['filename'] ) ){
    $download_location = "upload://{$_REQUEST['file']}";
    $filename = $_REQUEST['filename'];
    ob_clean();
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"" . $filename . "\""); 
    readfile($download_location);
}