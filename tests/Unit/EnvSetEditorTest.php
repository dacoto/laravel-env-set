<?php

declare(strict_types=1);

use dacoto\EnvSet\Exceptions\KeyNotFoundException;
use dacoto\EnvSet\Facades\EnvSet;

test('Get Keys', function () {
    expect(EnvSet::getKeys())->not->toBeNull();
});

test('Get Existing Key Value', function () {
    expect(EnvSet::getValue('ENV_VAR_1'))->toBe('ONE');
});

test('Create New Key', function () {
    EnvSet::setKey('TEST_VAR', 'testing');
    EnvSet::save();

    expect(EnvSet::getValue('TEST_VAR'))->toBe('testing');
});

test('Key Not Found Exception', function () {
    expect(fn() => EnvSet::getValue('NOT_FOUND_KEY'))->toThrow(KeyNotFoundException::class);
});
