<?php

require_once 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\S3Exception;
require_once("config.php"); 

function uploadToS3($result) {
    global $bucket, $awsCredentials;

    $mimeTypes = Array(
        'jpg' => 'image/jpg',
        'jpeg' => 'image/jpg',
        'zip' => 'application/zip',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'mp4' => 'video/mp4',
        'mov' => 'video/quicktime',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json'
    );
    $s3 = S3Client::factory($awsCredentials);
    
    foreach ($result as $file => $fullPath) {
        $extension = pathinfo(strtolower($fullPath), PATHINFO_EXTENSION);
        if ($extension === 'json') {
            $jsonData = json_decode(file_get_contents($fullPath));
            if ($jsonData === null) {
                echo 'WARNING: Invalid JSON data in file: ' . $fullPath;
            }
        }

        $mimeType = '';
        if (isset($mimeTypes[$extension])) {
            $mimeType = $mimeTypes[$extension];
        } else {
            throw new Exception("WARNING: $extension extension is not supported. Add it to MimeTypes.");
        }

        try {
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key' => $file,
                'SourceFile' => $fullPath,
                'ContentType' => $mimeType,
                'ACL' => CannedAcl::PUBLIC_READ
            ));
            $objectURL = $result['ObjectURL'];
            echo "<br> Complete. URL : <a href='$objectURL'>$objectURL</a>\n";
        } catch (S3Exception $exception) {
            echo $exception->getMessage() ."\n";
        }
    }
}

if ($_FILES)
{
    $temp_file = sys_get_temp_dir() . '/' .$_FILES["uploadedfile"]["name"];
    $filename = $_FILES["uploadedfile"]["tmp_name"]; 
    if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $temp_file)) {
        $results[$_FILES["uploadedfile"]["name"]] =  $temp_file; 
        uploadToS3($results); 
    }
}

?>
<html>
   <body>
    <form enctype="multipart/form-data" action="" method="POST">
    <h1>Choose a file to upload:</h1> <input name="uploadedfile" type="file" /><br />
    <input type="submit" value="Upload File" />
    </form>
   </body>
</html>
