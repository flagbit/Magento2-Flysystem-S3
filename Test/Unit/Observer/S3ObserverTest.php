<?php
namespace Flagbit\FlysystemS3\Test\Unit\Observer;

use \PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\MockObject\MockObject;
use \Magento\Framework\DataObject;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Logger\Monolog;
use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\FlysystemS3\Helper\Config;
use \Flagbit\FlysystemS3\Adapter\S3Manager;
use \Flagbit\FlysystemS3\Observer\S3Observer;

use \League\Flysystem\AwsS3v3\AwsS3Adapter;


class S3ObserverTest extends TestCase
{
    /**
     * @var FilesystemAdapterFactory|MockObject
     */
    protected $_flysystemFactoryMock;

    /**
     * @var S3Manager|MockObject
     */
    protected $_s3ManagerMock;

    /**
     * @var Config|MockObject
     */
    protected $_s3ConfigHelperMock;

    /**
     * @var Monolog|MockObject
     */
    protected $_loggerMock;

    /**
     * @var Observer|MockObject
     */
    protected $_observerMock;

    /**
     * @var DataObject|MockObject
     */
    protected $_dataObjectMock;

    /**
     * @var Manager|MockObject
     */
    protected $_managerMock;

    /**
     * @var AwsS3Adapter|MockObject
     */
    protected $_awsS3AdapterMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var S3Observer|MockObject
     */
    protected $_object;

    protected function setUp(): void
    {
        $this->_flysystemFactoryMock = $this->getMockBuilder(FilesystemAdapterFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_s3ManagerMock = $this->getMockBuilder(S3Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['createS3Driver'])
            ->getMock();

        $this->_s3ConfigHelperMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getS3ClientConfig', 'getS3Bucket', 'getS3Prefix'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEvent'])
            ->getMock();

        $this->_dataObjectMock = $this->getMockBuilder(DataObject::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData'])
            ->getMock();

        $this->_managerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['setAdapter'])
            ->getMock();

        $this->_awsS3AdapterMock = $this->getMockBuilder(AwsS3Adapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new S3Observer(
            $this->_flysystemFactoryMock,
            $this->_s3ManagerMock,
            $this->_s3ConfigHelperMock,
            $this->_loggerMock
        );
    }

    public function testExecute(): void
    {
        $source = 's3';
        $clientConfig = [
            'credentials' => [
                'key' => 'test1',
                'secret' => 'test2'
            ],
            'region' => 'test3',
            'version' => 'test4'
        ];
        $bucket = 'bucket';
        $prefix = 'prefix';

        $this->_observerMock->expects($this->any())
            ->method('getEvent')
            ->willReturn($this->_dataObjectMock);

        $this->_dataObjectMock->expects($this->at(0))
            ->method('getData')
            ->with('source')
            ->willReturn($source);

        $this->_dataObjectMock->expects($this->at(1))
            ->method('getData')
            ->with('manager')
            ->willReturn($this->_managerMock);

        $this->_s3ConfigHelperMock->expects($this->once())
            ->method('getS3ClientConfig')
            ->willReturn($clientConfig);

        $this->_s3ConfigHelperMock->expects($this->once())
            ->method('getS3Bucket')
            ->willReturn($bucket);

        $this->_s3ConfigHelperMock->expects($this->once())
            ->method('getS3Prefix')
            ->willReturn($prefix);

        $this->_s3ManagerMock->expects($this->once())
            ->method('createS3Driver')
            ->with($clientConfig, $bucket, $prefix)
            ->willReturn($this->_awsS3AdapterMock);

        $this->_flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->_awsS3AdapterMock)
            ->willReturn($this->_flysystemAdapterMock);

        $this->_managerMock->expects($this->once())
            ->method('setAdapter')
            ->with($this->_flysystemAdapterMock);

        $this->_object->execute($this->_observerMock);
    }

    public function testExecuteException(): void
    {
        $source = 's3';
        $clientConfig = [
            'credentials' => [
                'key' => 'invalid',
                'secret' => 'invalid'
            ],
            'region' => 'invalid',
            'version' => 'invalid'
        ];
        $bucket = 'invalid';
        $prefix = 'invalid';

        $message = 'test error';
        $exception = new \Exception($message);

        $this->_observerMock->expects($this->any())
            ->method('getEvent')
            ->willReturn($this->_dataObjectMock);

        $this->_dataObjectMock->expects($this->at(0))
            ->method('getData')
            ->with('source')
            ->willReturn($source);

        $this->_dataObjectMock->expects($this->at(1))
            ->method('getData')
            ->with('manager')
            ->willReturn($this->_managerMock);

        $this->_s3ConfigHelperMock->expects($this->once())
            ->method('getS3ClientConfig')
            ->willReturn($clientConfig);

        $this->_s3ConfigHelperMock->expects($this->once())
            ->method('getS3Bucket')
            ->willReturn($bucket);

        $this->_s3ConfigHelperMock->expects($this->once())
            ->method('getS3Prefix')
            ->willReturn($prefix);

        $this->_s3ManagerMock->expects($this->once())
            ->method('createS3Driver')
            ->with($clientConfig, $bucket, $prefix)
            ->willThrowException($exception);

        $this->_managerMock->expects($this->never())
            ->method('setAdapter');

        $this->_loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage());

        $this->_object->execute($this->_observerMock);
    }

    
    public function testExecuteWithInvalidSource(): void
    {
        $source = 'invalid';

        $this->_observerMock->expects($this->any())
            ->method('getEvent')
            ->willReturn($this->_dataObjectMock);

        $this->_dataObjectMock->expects($this->at(0))
            ->method('getData')
            ->with('source')
            ->willReturn($source);

        $this->_managerMock->expects($this->never())
            ->method('setAdapter');

        $this->_loggerMock->expects($this->never())
            ->method('critical');

        $this->_object->execute($this->_observerMock);
    }
}
