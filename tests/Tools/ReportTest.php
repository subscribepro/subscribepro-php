<?php

namespace SubscribePro\Tests\Tools;

use SubscribePro\Tools\Report;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\SubscribePro\Tools\Report
     */
    protected $reportTool;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    protected function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder('SubscribePro\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->reportTool = $this->getMockBuilder('SubscribePro\Tools\Report')
            ->setMethods(['isResource', 'isWritable'])
            ->setConstructorArgs([$this->httpClientMock])
            ->getMock();
    }

    /**
     * @expectedException \SubscribePro\Exception\InvalidArgumentException
     * @expectedExceptionMessageRegExp /Invalid report code. Allowed values: [a-z,_ ]+/
     */
    public function testFailToLoadReportIfReportCodeIsNotValid()
    {
        $this->httpClientMock->expects($this->never())
            ->method('getToSink');

        $this->reportTool->loadReport('some_invalid_code', 'filePath');
    }

    /**
     * @expectedException \SubscribePro\Exception\InvalidArgumentException
     * @expectedExceptionMessage file/path is not writable or a directory.
     */
    public function testFailToLoadReportIfUnableToWriteToFile()
    {
        $filePath = 'file/path';
        $code = Report::REPORT_CUSTOMER_ACTIVITY;

        $this->reportTool->expects($this->once())
            ->method('isResource')
            ->with($filePath)
            ->willReturn(false);

        $this->reportTool->expects($this->once())
            ->method('isWritable')
            ->with($filePath)
            ->willReturn(false);

        $this->httpClientMock->expects($this->never())
            ->method('getToSink');

        $this->reportTool->loadReport($code, $filePath);
    }

    public function testLoadReportIfFileIsResource()
    {
        $filePath = 'file/path';
        $code = Report::REPORT_CUSTOMER_ACTIVITY;

        $this->reportTool->expects($this->once())
            ->method('isResource')
            ->with($filePath)
            ->willReturn(true);

        $this->reportTool->expects($this->never())
            ->method('isWritable');

        $this->httpClientMock->expects($this->once())
            ->method('getToSink')
            ->with("/services/v2/reports/{$code}", $filePath);

        $this->reportTool->loadReport($code, $filePath);
    }

    public function testLoadReportIfFileIsWritable()
    {
        $filePath = 'file/path';
        $code = Report::REPORT_CUSTOMER_ACTIVITY;

        $this->reportTool->expects($this->once())
            ->method('isResource')
            ->with($filePath)
            ->willReturn(false);

        $this->reportTool->expects($this->once())
            ->method('isWritable')
            ->with($filePath)
            ->willReturn(true);

        $this->httpClientMock->expects($this->once())
            ->method('getToSink')
            ->with("/services/v2/reports/{$code}", $filePath);

        $this->reportTool->loadReport($code, $filePath);
    }
}
