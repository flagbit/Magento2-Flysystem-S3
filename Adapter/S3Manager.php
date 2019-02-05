<?php
namespace Flagbit\FlysystemS3\Adapter;
 
use \Magento\Framework\ObjectManagerInterface;

use \League\Flysystem\AwsS3v3\AwsS3Adapter;
use \League\Flysystem\AwsS3v3\AwsS3AdapterFactory;
use \Aws\S3\S3ClientFactory;

 
/**
 * Class S3Manager
 * @package Flagbit\FlysystemS3\Adapter
 */
class S3Manager implements S3ManagerInterface
{

    /**
     * @var AwsS3AdapterFactory
     */
    protected $awsS3AdapterFactory;

    /**
     * @var S3ClientFactory
     */
    protected $s3ClientFactory;

    /**
     * S3Manager constructor
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        AwsS3AdapterFactory $awsS3AdapterFactory,
        S3ClientFactory $s3ClientFactory
    ) {
        $this->awsS3AdapterFactory = $awsS3AdapterFactory;
        $this->s3ClientFactory = $s3ClientFactory;
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
        $s3client = $this->s3ClientFactory->create(['args' => $s3config]);

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
