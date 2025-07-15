<?php

namespace App\Console;

use App\Console\Commands\SendMailRappel;
use App\Helpers\MyHelper;
use App\Outil;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {



        $schedule->call(function () {
            //Outil::newrappelPaiement();
        })->dailyAt('09:52');

        $schedule->call(function () {
            //MyHelper::rappelEcheance();
        })->dailyAt('15:25');
        //

        $schedule->call(function () {
          //  MyHelper::rappelForRafAfterTwoDays();
        })->dailyAt('09:30');

        $schedule->call(function () {
            //MyHelper::relancePaiementRIDV2();
        })->dailyAt('12:15');
        // //


        $schedule->call(function () {
            Outil::sendeRelancePaiement();
        })->dailyAt('00:00');



    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
