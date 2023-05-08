<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\Credentials\CredentialProvider;

class S3_Composer_lib
{
    public function __construct()
    {
        $this->config = array(
            'region' => AWS_REGION,
            'version' => 'latest',
            # 자격증명
            'credentials' => array(
                'key'    => AWS_ACCESS_KEY,
                'secret' => AWS_SECRET_KEY,
            ),
        );
    }

    public function load()
    {
        #Bucket 연결 객체
        $s3Client = new S3Client($this->config);

        return $s3Client;
    }
}