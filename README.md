# aws-s3-upload-html-php
This sets up a simple web page to upload S3 assets to a bucket.  Example: This let's you uplaod a image file and get a URL back. 

<img src="https://afterschool-mobile-configurations.s3.amazonaws.com/Screen%20Shot%202019-01-16%20at%2012.50.14%20PM.png"  width="100px" >


# How to run it 

Create a config.php file by copying config.sample.php and filling out your AWS credentials

```php

$bucket = "bucket-name";
$awsCredentials = array(
    'credentials' => array(
        'key'    => '[YOUR-AWS-KEY]',
        'secret' => '[YOUR-AWS-SECRET',
    )
);
```

To start: 

    docker-compose up
    
To use: 
  
   http://localhost:8080/upload.php
   
