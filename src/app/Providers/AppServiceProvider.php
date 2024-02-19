<?php

namespace App\Providers;

use App\Models\Fund;
use Illuminate\Contracts\Foundation\Application;
use App\Http\Controllers\FundApiController;
use App\Http\Mapper\FundMapper;
use App\Http\ViewModels\FundViewModel;
use App\Repositories\FundRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {
        // If the application grows in scope, ideally this would be moved to factory classes.
        $this->app->bind(
            FundRepository::class,
            function () {
                return new FundRepository(new Fund());
            }
        );

        $this->app->bind(
            FundApiController::class,
            function (Application $app) {

                $fundMapper     = new FundMapper();
                $fundViewModel  = new FundViewModel();
                $fundRepository = $app->make(FundRepository::class);

                return new FundApiController($fundRepository, $fundViewModel, $fundMapper);
            }
        );

    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }


}
