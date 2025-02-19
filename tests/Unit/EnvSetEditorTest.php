<?php

declare(strict_types=1);

namespace dacoto\EnvSet\Tests\Unit;

use dacoto\EnvSet\Exceptions\KeyNotFoundException;
use dacoto\EnvSet\Facades\EnvSet;
use dacoto\EnvSet\Tests\TestCase;

class EnvSetEditorTest extends TestCase
{
    /** @test */
    public function it_gets_keys(): void
    {
        $this->assertIsArray(EnvSet::getKeys());
    }

    /** @test */
    public function it_gets_existing_key_value(): void
    {
        EnvSet::setKey('ENV_VAR_1', 'ONE');
        EnvSet::save();

        $this->assertSame('ONE', EnvSet::getValue('ENV_VAR_1'));
    }

    /** @test */
    public function it_creates_a_new_key(): void
    {
        EnvSet::setKey('TEST_VAR', 'testing');
        EnvSet::save();

        $this->assertSame('testing', EnvSet::getValue('TEST_VAR'));
    }

    /** @test */
    public function it_deletes_a_key(): void
    {
        EnvSet::setKey('DELETE_ME', 'value');
        EnvSet::save();

        EnvSet::deleteKey('DELETE_ME');
        EnvSet::save();

        $this->assertFalse(EnvSet::keyExists('DELETE_ME'));
    }

    /** @test */
    public function it_deletes_multiple_keys(): void
    {
        EnvSet::setKeys([
            'DELETE_ONE' => 'value1',
            'DELETE_TWO' => 'value2'
        ]);
        EnvSet::save();

        EnvSet::deleteKeys(['DELETE_ONE', 'DELETE_TWO']);
        EnvSet::save();

        $this->assertFalse(EnvSet::keyExists('DELETE_ONE'));
        $this->assertFalse(EnvSet::keyExists('DELETE_TWO'));
    }

    /** @test */
    public function it_checks_if_a_key_exists(): void
    {
        EnvSet::setKey('EXISTING_VAR', 'value');
        EnvSet::save();

        $this->assertTrue(EnvSet::keyExists('EXISTING_VAR'));
        $this->assertFalse(EnvSet::keyExists('NON_EXISTENT_VAR'));
    }

    /** @test */
    public function it_throws_exception_when_key_not_found(): void
    {
        $this->expectException(KeyNotFoundException::class);
        EnvSet::getValue('NOT_FOUND_KEY');
    }

    /** @test */
    public function it_returns_default_value_if_key_does_not_exist(): void
    {
        $this->assertSame('default_value', EnvSet::getValue('MISSING_VAR', 'default_value'));
        $this->assertSame('', EnvSet::getValue('MISSING_VAR', ''));
        $this->assertFalse(EnvSet::getValue('MISSING_VAR', false));
    }

    /** @test */
    public function it_adds_a_comment_to_the_env_file(): void
    {
        EnvSet::addComment('This is a test comment');
        EnvSet::save();

        $content = EnvSet::getContent();
        $this->assertStringContainsString('# This is a test comment', $content);
    }

    /** @test */
    public function it_adds_an_empty_line(): void
    {
        EnvSet::addEmpty();
        EnvSet::save();

        $content = EnvSet::getContent();
        $this->assertStringContainsString("\n\n", $content);
    }
}
