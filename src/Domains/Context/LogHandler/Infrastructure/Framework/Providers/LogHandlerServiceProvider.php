<?php

namespace Domains\Context\LogHandler\Infrastructure\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Domains\Context\LogHandler\Application\Services\Spreadsheet\SpreadsheetExtractor;
use Domains\Context\LogHandler\Application\UseCases\Spreadsheet\ExtractSpreadsheetUseCase;
use Domains\Context\LogHandler\Application\UseCases\Spreadsheet\IExtractSpreadsheetUseCase;
use Domains\Context\LogHandler\Domain\Model\SpreadsheetSource\SpreadsheetSource;
use Domains\Context\LogHandler\Outbound\ExtractSpreadsheetOutputPort;

class LogHandlerServiceProvider extends ServiceProvider
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

        ## USE CASE - Importing a spreadsheet   ##
        $this->app->singleton(
            IExtractSpreadsheetUseCase::class,
            function () {
                $outputPort = new ExtractSpreadsheetOutputPort();
                $dataSource = new SpreadsheetSource();
                $spreadsheetExtractor = new SpreadsheetExtractor();
                return new ExtractSpreadsheetUseCase($dataSource, $spreadsheetExtractor, $outputPort);
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
                __DIR__ . '/../Config/config.php' => config_path('LogHandler.php'),
            ],
            'config'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'LogHandler'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/LogHandler');

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
                        return $path . '/modules/LogHandler';
                    },
                    \Config::get('view.paths')
                ),
                [$sourcePath]
            ),
            'LogHandler'
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/LogHandler');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'LogHandler');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'LogHandler');
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
