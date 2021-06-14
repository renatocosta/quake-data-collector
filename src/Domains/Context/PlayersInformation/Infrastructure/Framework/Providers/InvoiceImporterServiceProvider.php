<?php

namespace Domains\Context\PlayersInformation\Infrastructure\Framework\Providers;

use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Domains\Context\Core\Entities\Invoice;
use Domains\Context\PlayersInformation\Application\EventHandlers\DataSource\DataSourceWasCreatedEventHandler;
use Domains\Context\PlayersInformation\Application\Services\DataSource\InvoiceData;
use Domains\Context\PlayersInformation\Application\UseCases\CreateDataSource\CreateDataSourceUseCase;
use Domains\Context\PlayersInformation\Application\UseCases\CreateDataSource\ICreateDataSourceUseCase;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSource;
use Domains\Context\PlayersInformation\Infrastructure\DataAccess\Repositories\DataSourceRepository;
use Domains\Context\PlayersInformation\Infrastructure\DataAccess\UnitOfWork;
use Domains\Context\PlayersInformation\Infrastructure\Framework\Entities\InvoiceDataSource;
use Domains\Context\PlayersInformation\Infrastructure\Framework\Entities\InvoiceDataSourceItems;
use Domains\Context\PlayersInformation\Outbound\CreateDataSourceOutputPort;
use Domains\Context\PlayersInformation\Application\Services\DataSource\DataSourceItemsMapper;
use Domains\Context\PlayersInformation\Application\Services\DataSource\AssetCodeMissing;
use Domains\Context\PlayersInformation\Application\Services\DataSource\PoNumberMissing;
use Domains\Context\PlayersInformation\Application\Services\DataSource\ExternalInvoiceIdMissing;
use Domains\Context\PlayersInformation\Application\Services\DataSource\AssetCodeNotFound;
use Domains\Context\PlayersInformation\Application\Services\DataSource\PoNumberNotFound;
use Domains\Context\PlayersInformation\Application\Services\DataSource\DefaultItemStatusCriteria;
use Domains\Context\Trade\Entities\PurchaseOrder;
use Domains\Context\Asset\Entities\Asset;
use Domains\Context\PlayersInformation\Application\Services\DataSource\InvoiceValueChanged;
use Domains\Context\PlayersInformation\Application\Services\DataSource\PoNumberDuplicate;
use Domains\Context\PlayersInformation\Application\Services\DataSource\RecurringRegistryEntries;
use Domains\Context\PlayersInformation\Application\Services\DataSource\Reporting\DataSourceItemsSummaryAll;
use Domains\Context\PlayersInformation\Application\Services\DataSource\Reporting\Scenario\DataSourceItemsReportingByInvoiceDataSource;
use Domains\Context\PlayersInformation\Application\Services\DataSource\Reporting\DataSourceItemsSummaryCounting;
use Domains\Context\PlayersInformation\Application\Services\DataSource\SuccessfullyImportedPreviously;
use Domains\Context\PlayersInformation\Application\UseCases\Reporting\IReportingUseCase;
use Domains\Context\PlayersInformation\Application\UseCases\Reporting\ReportingUseCase;
use Domains\Context\PlayersInformation\Application\UseCases\ReportingBySource\IReportingBySourceUseCase;
use Domains\Context\PlayersInformation\Application\UseCases\ReportingBySource\ReportingBySourceUseCase;
use Domains\Context\PlayersInformation\Domain\Specifications\DataSourceFileItemRepeated;
use Domains\Context\PlayersInformation\Domain\Specifications\DataSourceItemCanBeATrade;
use Domains\Context\PlayersInformation\Domain\Specifications\DataSourceItemWasPreviouslyAdded;
use Domains\Context\PlayersInformation\Domain\Specifications\DataSourceItemWasPreviouslyAddedInRepo;
use Domains\Context\PlayersInformation\Domain\Specifications\DataSourceRecurringRegistryEntries;

class InvoiceImporterServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        ## USE CASE - Create Data Source ##
        $this->app->singleton(
            ICreateDataSourceUseCase::class,
            function () {
                $unitOfWork = new UnitOfWork();
                $invoiceDataSourceItems = new InvoiceDataSourceItems();
                $dataSourceRepository = new DataSourceRepository(new InvoiceDataSource(), $invoiceDataSourceItems);

                $statusItemFlow = new AssetCodeMissing();
                $statusItemFlow->linkNext(new PoNumberMissing())
                    ->linkNext(new ExternalInvoiceIdMissing())
                    ->linkNext(new AssetCodeNotFound(new Asset()))
                    ->linkNext(new PoNumberDuplicate())
                    ->linkNext(new PoNumberNotFound())
                    ->linkNext(new RecurringRegistryEntries())
                    ->linkNext(new InvoiceValueChanged())
                    ->linkNext(new SuccessfullyImportedPreviously())
                    ->linkNext(new DefaultItemStatusCriteria());
                $dataSourceRecurringRegistryEntries = new DataSourceRecurringRegistryEntries();
                $dataSourceItemWasPreviouslyAddedInRepo = new DataSourceItemWasPreviouslyAddedInRepo($dataSourceRepository);
                $dataSourceItemsMapper = new DataSourceItemsMapper($statusItemFlow, new DataSourceItemWasPreviouslyAdded($dataSourceItemWasPreviouslyAddedInRepo, $dataSourceRecurringRegistryEntries), new DataSourceItemCanBeATrade(new PurchaseOrder()));

                $dataSourceItemsReporting = new DataSourceItemsReportingByInvoiceDataSource($dataSourceRepository);
                $dataSourceItemsReporting->addSummary(new DataSourceItemsSummaryCounting());

                $outputPort = new CreateDataSourceOutputPort(new InvoiceData(new Invoice(), $invoiceDataSourceItems), $dataSourceItemsReporting);
                $domainEventBus = new DomainEventBus();
                $domainEventBus->subscribe(new DataSourceWasCreatedEventHandler($dataSourceRepository));
                $dataSource = new DataSource($domainEventBus);
                return new CreateDataSourceUseCase($dataSource, $dataSourceItemsMapper, $unitOfWork, $outputPort);
            }
        );


        ## USE CASE - Reporting all ##
        $this->app->singleton(
            IReportingUseCase::class,
            function () {
                $invoiceDataSourceItems = new InvoiceDataSourceItems();
                $dataSourceRepository = new DataSourceRepository(new InvoiceDataSource(), $invoiceDataSourceItems);
                return new ReportingUseCase($dataSourceRepository);
            }
        );

        ## USE CASE - Reporting by source ##
        $this->app->singleton(
            IReportingBySourceUseCase::class,
            function () {
                $invoiceDataSourceItems = new InvoiceDataSourceItems();
                $dataSourceRepository = new DataSourceRepository(new InvoiceDataSource(), $invoiceDataSourceItems);
                $dataSourceItemsReporting = new DataSourceItemsReportingByInvoiceDataSource($dataSourceRepository);
                $dataSourceItemsReporting->addSummary(new DataSourceItemsSummaryCounting());
                $dataSourceItemsReporting->addSummary(new DataSourceItemsSummaryAll());
                return new ReportingBySourceUseCase($dataSourceItemsReporting);
            }
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes(
            [
                __DIR__ . '/../Config/config.php' => config_path('players_information.php'),
            ],
            'config'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'players_information'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/players_information');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes(
            [
                $sourcePath => $viewPath
            ],
            'views'
        );

        $this->loadViewsFrom(
            array_merge(
                array_map(
                    function ($path) {
                        return $path . '/modules/players_information';
                    },
                    \Config::get('view.paths')
                ),
                [$sourcePath]
            ),
            'players_information'
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/players_information');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'players_information');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'players_information');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
