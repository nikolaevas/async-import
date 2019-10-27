<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AsynchronousImportDataExchanging\Model\ImportValidator;

use Magento\AsynchronousImportDataExchangingApi\Api\Data\ImportInterface;
use Magento\TestFramework\Helper\Bootstrap;

class UuidValidatorTest extends \PHPUnit\Framework\TestCase
{
    const VALID_UUID = 'fe563e12-cf9d-4faf-82cd-96e011b557b7';
    const INVALID_UUID = 'abcdef';

    protected $objectManager;

    protected $uuidValidator;

    protected $import;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->uuidValidator = $this->objectManager->create(UuidValidator::class);
        $this->import = $this->getMockBuilder(\Magento\AsynchronousImportDataExchanging\Model\Import::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUuid'])
            ->getMock();
    }

    public function testNoErrorMessages()
    {
        $this->import->method('getUuid')
            ->willReturn(self::VALID_UUID);

        $validationResult = $this->uuidValidator->validate($this->import);
        $errors = $validationResult->getErrors();

        $this->assertCount(0, $errors);
    }

    public function testEmptyField()
    {
        $this->import->method('getUuid')
            ->willReturn('');

        $validationResult = $this->uuidValidator->validate($this->import);
        $errors = $validationResult->getErrors();

        $this->assertCount(1, $errors);

        $this->assertEquals(
            __('"%field" can not be empty.', ['field' => ImportInterface::UUID]),
            $errors[0]
        );
    }

    public function testUuidIsNotValid()
    {
        $this->import->method('getUuid')
            ->willReturn(self::INVALID_UUID);

        $validationResult = $this->uuidValidator->validate($this->import);
        $errors = $validationResult->getErrors();

        $this->assertCount(1, $errors);

        $this->assertEquals(
            __('"The uuid "%uuid" is not valid.', ['uuid' => self::INVALID_UUID]),
            $errors[0]
        );
    }
}
