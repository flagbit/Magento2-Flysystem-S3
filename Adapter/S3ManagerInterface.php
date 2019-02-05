<?php
namespace Flagbit\FlysystemS3\Adapter;

use \League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * Interface S3ManagerInterface
 * @package Flagbit\FlysystemS3\Adapter
 */
interface S3ManagerInterface
{
    /**
     * @param array $s3config
     * @param string $bucket
     * @param string $prefix
     * @param array $options
     * @return AwsS3Adapter
     */
    public function createS3Driver(array $s3config, string $bucket, string $prefix = '', array $options = []);
}
