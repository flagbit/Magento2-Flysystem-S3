<?php
namespace Flagbit\FlysystemS3\Test\Unit\Adapter;

use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;
use \Flagbit\FlysystemS3\Adapter\S3Manager;
use \League\Flysystem\AwsS3v3\AwsS3Adapter;
use \League\Flysystem\AwsS3v3\AwsS3AdapterFactory;
use \Aws\S3\S3Client;

class FilesystemManagerTest extends TestCase
{
    /**
     * @var AwsS3AdapterFactory|MockObject
     */
    protected $_awsS3AdapterFactoryMock;

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
        $this->_awsS3AdapterFactoryMock = $this->getMockBuilder(AwsS3AdapterFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_awsS3AdapterMock = $this->getMockBuilder(AwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new S3Manager(
            $this->_awsS3AdapterFactoryMock
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

        $this->_awsS3AdapterFactoryMock->expects($this->once())
            ->method('create')
            ->with([
                'client' => $s3ClientResult,
                'bucket' => $bucket,
                'prefix' => $prefix,
                'options' => []
            ])
            ->willReturn($this->_awsS3AdapterMock);

        $this->assertEquals($this->_awsS3AdapterMock, $this->_object->createS3Driver($s3config, $bucket, $prefix));
    }
}
