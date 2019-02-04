<?php
namespace Flagbit\FlysystemS3\Test\Unit\Adapter;

use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;
use \Magento\Framework\App\ObjectManager;
use \Flagbit\FlysystemS3\Adapter\S3Manager;
use \League\Flysystem\AwsS3v3\AwsS3Adapter;
use \Aws\S3\S3Client;

class FilesystemManagerTest extends TestCase
{
    /**
     * @var ObjectManager|MockObject
     */
    protected $_objectManagerMock;

    /**
     * @var AwsS3Adapter|MockObject
     */
    protected $_awsS3AdapterMock;

    /**
     * @var S3Manager
     */
    protected $_object;

    protected function setUp(): void
    {
        $this->_objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_awsS3AdapterMock = $this->getMockBuilder(AwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new S3Manager(
            $this->_objectManagerMock
        );
    }

    public function testCreateS3Driver(): void
    {
        $s3config = [
            'credentials' => [
                'key'    => 'test',
                'secret' => 'test',
            ],
            'region' => 'test',
            'version' => 'latest',
        ];
        $bucket = 'test';
        $prefix = 'test';

        $s3ClientResult = S3Client::factory($s3config);

        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with(AwsS3Adapter::class, [
                'client' => $s3ClientResult,
                'bucket' => $bucket,
                'prefix' => $prefix,
                'options' => []
            ])
            ->willReturn($this->_awsS3AdapterMock);

        $this->assertEquals($this->_awsS3AdapterMock, $this->_object->createS3Driver($s3config, $bucket, $prefix));
    }
}