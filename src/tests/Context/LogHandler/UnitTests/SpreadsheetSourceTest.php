<?php

namespace Tests\Context\LogHandler\UnitTests;

use DG\BypassFinals;
use Exception;
use Tests\TestCase;

class SpreadsheetSourceTest extends TestCase
{

    use \Tests\Context\LogHandler\LogHandlerFactoryTestProvider;
    use \Tests\Context\LogHandler\LogHandlerTestProvider;

    public static function setUpBeforeClass(): void
    {
        BypassFinals::enable();
    }

    public function setup(): void
    {
        parent::setUp();
        $this->loadDependencies();
    }

    public function testShouldFailToReadRowsWhenCountableHaveZeroRows()
    {
        $this->spreadsheetSource->extractFrom($this->zeroRows);
        $this->assertFalse($this->spreadsheetSource->isValid());
    }

    public function testShouldFailToReadRowsWhenCountableHaveHeaderAndZeroRows()
    {
        $this->spreadsheetSource->isSkipHeader = true;
        $this->spreadsheetSource->extractFrom($this->zeroRowsWithHeader);
        $this->assertFalse($this->spreadsheetSource->isValid());
    }

    public function testShouldFailToUploadedFileForInvalidType()
    {
        $this->expectException(\Exception::class);
        $validFile = sprintf('%s%s%s', dirname(__FILE__),  '/../bucket/', $this->uploadFiles['invalid_and_unexpected_type']);
        $this->spreadsheetExtractor->setup($validFile);
    }
}
