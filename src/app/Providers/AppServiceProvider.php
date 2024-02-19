<?php

namespace App\Providers;

use App\Http\Controllers\FundDuplicatesApiController;
use App\Http\Mapper\FundDuplicatesMapper;
use App\Http\ViewModels\FundDuplicatesViewModel;
use App\Models\Fund;
use App\Models\FundDuplicatesCandidate;
use App\Repositories\FundDuplicatesRepository;
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
            function (Application $app) {
                return new FundRepository($app->make(Fund::class));
            }
        );

        $this->app->bind(
            FundApiController::class,
            function (Application $app) {

                $fundMapper     = $app->make(FundMapper::class);
                $fundViewModel  = $app->make(FundViewModel::class);
                $fundRepository = $app->make(FundRepository::class);

                return new FundApiController($fundRepository, $fundViewModel, $fundMapper);
            }
        );

        $this->app->bind(
            FundDuplicatesRepository::class,
            function (Application $app) {
                return new FundRepository($app->make(FundDuplicatesCandidate::class));
            }
        );

        $this->app->bind(
            FundDuplicatesApiController::class,
            function (Application $app) {

                $fundMapper     = $app->make(FundDuplicatesMapper::class);
                $fundViewModel  = $app->make(FundDuplicatesViewModel::class);
                $fundRepository = $app->make(FundDuplicatesRepository::class);

                return new FundDuplicatesApiController($fundRepository, $fundViewModel, $fundMapper);
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
