<?php
namespace Flagbit\FlysystemS3\Adapter;
 
use \Magento\Framework\ObjectManagerInterface;
use \Flagbit\Flysystem\Adapter\FilesystemManager;

use \League\Flysystem\AwsS3v3\AwsS3Adapter;
use \League\Flysystem\AwsS3v3\AwsS3AdapterFactory;
use \Aws\S3\S3Client;

 
/**
 * Class S3Manager
 * @package Flagbit\FlysystemS3\Adapter
 */
class S3Manager extends FilesystemManager
{

    protected $awsS3AdapterFactory;

    /**
     * S3Manager constructor
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        AwsS3AdapterFactory $awsS3AdapterFactory
    ) {
        $this->awsS3AdapterFactory = $awsS3AdapterFactory;
    }

    /**
     * Create s3 driver
     *
     * @param array $s3config
     * @param string $bucket
     * @param string $prefix
     * @param array $options
     * @return AwsS3Adapter
     */
    public function createS3Driver(array $s3config, string $bucket, string $prefix = '', array $options = []): AwsS3Adapter
    {
        $s3client = S3Client::factory($s3config);

        return $this->awsS3AdapterFactory->create(
            [
                'client' => $s3client,
                'bucket' => $bucket,
                'prefix' => $prefix,
                'options' => $options
            ]
        );

    }
}
