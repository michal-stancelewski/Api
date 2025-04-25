<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

final readonly class ValidatorService
{
    public function validate(object $object): array
    {
        $errors = [];

        $violations = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator()
            ->validate($object);

        /** @var ConstraintViolationList $violations */
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath() . ' ' . $violation->getMessage();
            }
        }

        return $errors;
    }
}
