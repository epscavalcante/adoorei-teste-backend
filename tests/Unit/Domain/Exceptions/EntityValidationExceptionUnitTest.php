<?php

use Core\Domain\Exceptions\EntityValidationException;

describe('EntityValidationExceptionUnitTest', function () {
    test('should throw error', function () {
        throw new EntityValidationException('Error', 'test');
    })->throws(EntityValidationException::class, 'The given data was invalid.');

    test('should return errors when send string error ', function () {
        try {
            throw new EntityValidationException('Error', 'test');
        } catch (EntityValidationException $e) {

            expect($e->getMessage())->toBe('The given data was invalid.');
            expect($e->getErrors())->toMatchArray([
                'test' => ['Error'],
            ]);
        }
    });

    test('should return errors when send array error ', function () {
        try {
            throw new EntityValidationException(
                ['Error 1', 'Error 2'],
                'test'
            );
        } catch (EntityValidationException $e) {

            expect($e->getMessage())->toBe('The given data was invalid.');
            expect($e->getErrors())->toMatchArray([
                'test' => ['Error 1', 'Error 2'],
            ]);
        }
    });
});
