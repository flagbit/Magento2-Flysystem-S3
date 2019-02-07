<?php
namespace Flagbit\FlysystemS3\Test\Unit\Adapter;

use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;
use \Flagbit\FlysystemS3\Adapter\S3Manager;
use \League\Flysystem\AwsS3v3\AwsS3Adapter;
use \League\Flysystem\AwsS3v3\AwsS3AdapterFactory;
use \Aws\S3\S3Client;
use \Aws\S3\S3ClientFactory;

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
     * @var S3ClientFactory|MockObject
     */
    protected $_s3ClientFactoryMock;

    /**
     * @var S3Client|MockObject
     */
    protected $_s3ClientMock;

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

        $this->_s3ClientFactoryMock = $this->getMockBuilder(S3ClientFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_s3ClientMock = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new S3Manager(
            $this->_awsS3AdapterFactoryMock,
            $this->_s3ClientFactoryMock
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

        $this->_s3ClientFactoryMock->expects($this->once())
            ->method('create')
            ->with(['args' => $s3config])
            ->willReturn($this->_s3ClientMock);

        $this->_awsS3AdapterFactoryMock->expects($this->once())
            ->method('create')
            ->with([
                'client' => $this->_s3ClientMock,
                'bucket' => $bucket,
                'prefix' => $prefix,
                'options' => []
            ])
            ->willReturn($this->_awsS3AdapterMock);

        $this->assertEquals($this->_awsS3AdapterMock, $this->_object->createS3Driver($s3config, $bucket, $prefix));
    }
}
