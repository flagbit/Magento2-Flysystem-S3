<?php
namespace Flagbit\FlysystemS3\Test\Unit\Helper;

use \PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\MockObject\MockObject;
use \Magento\Framework\App\Config;
use \Flagbit\FlysystemS3\Helper\Config as FlysystemConfig;


class ConfigTest extends TestCase
{
    /**
     * @var Config|MockObject
     */
    protected $_scopeConfigMock;

    /**
     * @var FlysystemConfig|MockObject
     */
    protected $_object;

    protected function setUp(): void
    {
        $this->_scopeConfigMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getValue'])
            ->getMock();

        $this->_object = new FlysystemConfig(
            $this->_scopeConfigMock
        );
    }

    public function testGetS3Key(): void
    {
        $result = 'testKey';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_KEY)
            ->willReturn($result);

        $this->assertEquals($result, $this->_object->getS3Key());
    }

    public function testGetS3Secret(): void
    {
        $result = 'testSecret';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_SECRET)
            ->willReturn($result);

        $this->assertEquals($result, $this->_object->getS3Secret());
    }

    public function testGetS3Region(): void
    {
        $result = 'testRegion';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_REGION)
            ->willReturn($result);

        $this->assertEquals($result, $this->_object->getS3Region());
    }

    public function testGetS3Version(): void
    {
        $result = 'testVersion';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_VERSION)
            ->willReturn($result);

        $this->assertEquals($result, $this->_object->getS3Version());
    }

    public function testGetS3Bucket(): void
    {
        $result = 'testBucket';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_BUCKET)
            ->willReturn($result);

        $this->assertEquals($result, $this->_object->getS3Bucket());
    }

    public function testGetS3Prefix(): void
    {
        $result = 'testPrefix';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_PREFIX)
            ->willReturn($result);

        $this->assertEquals($result, $this->_object->getS3Prefix());
    }

    public function testGetS3ClientConfig(): void
    {
        $expectedResult = [
            'credentials' => [
                'key' => 'test1',
                'secret' => 'test2'
            ],
            'region' => 'test3',
            'version' => 'test4'
        ];

        $this->_scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_KEY)
            ->willReturn($expectedResult['credentials']['key']);

        $this->_scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_SECRET)
            ->willReturn($expectedResult['credentials']['secret']);

        $this->_scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_REGION)
            ->willReturn($expectedResult['region']);

        $this->_scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_S3_VERSION)
            ->willReturn($expectedResult['version']);

        $this->assertEquals($expectedResult, $this->_object->getS3ClientConfig());
    }
}
