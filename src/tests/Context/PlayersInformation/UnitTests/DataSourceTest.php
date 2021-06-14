<?php

namespace Tests\Context\PlayersInformation\UnitTests;

use DG\BypassFinals;
use Exception;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\SourceType;
use Tests\TestCase;

class DataSourceTest extends TestCase
{

    use \Tests\Context\PlayersInformation\PlayersInformationFactoryTestProvider;
    use \Tests\Context\PlayersInformation\PlayersInformationTestProvider;

    public static function setUpBeforeClass(): void
    {
        BypassFinals::enable();
    }

    public function setup(): void
    {
        parent::setUp();
        $this->loadPlayersInformationDependencies();
        $this->loadProviders();
    }

    public function testFailToDataSourceIfStrucutureIsInvalid()
    {
        $this->dataSource->createFrom(new SourceType($this->invalidData['source_type']), $this->invalidData['remote_file_id'], $this->invalidData['company_id'], $this->invalidData['items']);
        $this->assertFalse($this->dataSource->isValid());
    }

    /**
     * @dataProvider invalidDataSourceHeader
     */
    public function testFailToDataSourceIfReferenceStrucuturedIsInvalid($remoteFileId, $companyId, $sourceType)
    {
        $this->dataSource->createFrom(new SourceType($sourceType), $remoteFileId, $companyId, $this->validData['items']);
        $this->assertFalse($this->dataSource->isValid());
    }
}
