<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportDataExchanging\Model\ImportValidator;

use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use Magento\AsynchronousImportDataExchangingApi\Api\Data\ImportInterface;
use Magento\AsynchronousImportDataExchangingApi\Model\ImportValidatorInterface;
use Magento\Framework\DataObject\IdentityValidatorInterface;

/**
 * Check that "uuid" value is valid
 */
class UuidValidator implements ImportValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @var IdentityValidatorInterface
     */
    private $identityValidator;

    /**
     * @param ValidationResultFactory    $validationResultFactory
     * @param IdentityValidatorInterface $identityValidator
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory,
        IdentityValidatorInterface $identityValidator
    ) {
        $this->validationResultFactory = $validationResultFactory;
        $this->identityValidator = $identityValidator;
    }

    /**
     * @inheritdoc
     */
    public function validate(ImportInterface $import): ValidationResult
    {
        $value = (string)$import->getUuid();

        if ('' === trim($value)) {
            $errors[] = __('"%field" can not be empty.', ['field' => ImportInterface::UUID]);
        } elseif (!$this->identityValidator->isValid($value)) {
            $errors[] = __('"The uuid "%uuid" is not valid.', ['uuid' => $value]);
        } else {
            $errors = [];
        }
        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}
