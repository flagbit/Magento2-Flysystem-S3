<?php
namespace Flagbit\FlysystemS3\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Flysystem S3 Config Helper
 * @package Flagbit\FlysystemS3\Helper
 */
class Config {

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    const XPATH_CONFIG_S3_KEY = 'flagbit_flysystem/s3/key';
    const XPATH_CONFIG_S3_SECRET = 'flagbit_flysystem/s3/secret';
    const XPATH_CONFIG_S3_REGION = 'flagbit_flysystem/s3/region';
    const XPATH_CONFIG_S3_VERSION = 'flagbit_flysystem/s3/version';
    const XPATH_CONFIG_S3_BUCKET = 'flagbit_flysystem/s3/bucket';
    const XPATH_CONFIG_S3_PREFIX = 'flagbit_flysystem/s3/prefix';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getS3Key()
    {
        return (string)$this->_scopeConfig->getValue(self::XPATH_CONFIG_S3_KEY);
    }

    /**
     * @return string
     */
    public function getS3Secret()
    {
        return (string)$this->_scopeConfig->getValue(self::XPATH_CONFIG_S3_SECRET);
    }

    /**
     * @return string
     */
    public function getS3Region()
    {
        return (string)$this->_scopeConfig->getValue(self::XPATH_CONFIG_S3_REGION);
    }

    /**
     * @return string
     */
    public function getS3Version()
    {
        return (string)$this->_scopeConfig->getValue(self::XPATH_CONFIG_S3_VERSION);
    }

    /**
     * @return string
     */
    public function getS3Bucket()
    {
        return (string)$this->_scopeConfig->getValue(self::XPATH_CONFIG_S3_BUCKET);
    }

    /**
     * @return string
     */
    public function getS3Prefix()
    {
        return (string)$this->_scopeConfig->getValue(self::XPATH_CONFIG_S3_PREFIX);
    }

    /**
     * @return array
     */
    public function getS3ClientConfig()
    {
        return [
            'credentials' => [
                'key' => $this->getS3Key(),
                'secret' => $this->getS3Secret()
            ],
            'region' => $this->getS3Region(),
            'version' => $this->getS3Version()
        ];
    }
}