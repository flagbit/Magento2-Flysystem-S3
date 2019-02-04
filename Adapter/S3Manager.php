<?php
namespace Flagbit\FlysystemS3\Adapter;
 
use \Magento\Framework\ObjectManagerInterface;
use \League\Flysystem\AwsS3v3\AwsS3Adapter;
use \Aws\S3\S3Client;
 
/**
 * Class S3Manager
 * @package Flagbit\FlysystemS3\Adapter
 */
class S3Manager extends \Flagbit\Flysystem\Adapter\FilesystemManager
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * S3Manager constructor
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * Create s3 driver
     *
     * @param array $s3config
     * @param string $bucket
     * @param string $prefix
     * @param array $options
     * @return mixed
     */
    public function createS3Driver(array $s3config, string $bucket, string $prefix = '', array $options = [])
    {
        $s3client = S3Client::factory($s3config);

        return $this->objectManager->create(AwsS3Adapter::class, [
            'client' => $s3client,
            'bucket' => $bucket,
            'prefix' => $prefix,
            'options' => $options
        ]);
    }
}