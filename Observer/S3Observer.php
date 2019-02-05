<?php
namespace Flagbit\FlysystemS3\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\FlysystemS3\Adapter\S3Manager;
use \Flagbit\FlysystemS3\Helper\Config;
use \Psr\Log\LoggerInterface;

/**
 * Obsever for Flysystem S3 Adapter
 * hooks event flagbit_flysystem_create_after
 * @package Flagbit\FlysystemS3\Observer
 */
class S3Observer implements ObserverInterface
{
    /**
     * @var FilesystemAdapterFactory
     */
    protected $_flysystemFactory;

    /**
     * @var S3Manager
     */
    protected $_s3Manager;

    /**
     * @var Config
     */
    protected $_s3ConfigHelper;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @param FilesystemAdapterFactory $flysystemFactory
     * @param S3Manager $s3Manager
     * @param Config $s3ConfigHelper
     */
    public function __construct(
        FilesystemAdapterFactory $flysystemFactory,
        S3Manager $s3Manager,
        Config $s3ConfigHelper,
        LoggerInterface $logger
    ) {
        $this->_flysystemFactory = $flysystemFactory;
        $this->_s3Manager = $s3Manager;
        $this->_s3ConfigHelper = $s3ConfigHelper;
        $this->_logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        try {
            $source = $observer->getEvent()->getData('source');

            if ($source === 's3') {
                $manager = $observer->getEvent()->getData('manager');

                $clientConfig = $this->_s3ConfigHelper->getS3ClientConfig();
                $bucket = $this->_s3ConfigHelper->getS3Bucket();
                $prefix = $this->_s3ConfigHelper->getS3Prefix();

                $driver = $this->_s3Manager->createS3Driver(
                    $clientConfig,
                    $bucket,
                    $prefix
                );

                $adapter = $this->_flysystemFactory->create($driver);
                $manager->setAdapter($adapter);
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}
