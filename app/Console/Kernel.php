<?php

namespace App\Console;

use App\Models\SystemLock;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // GERA PARCELAS TODO MÊS
        $schedule->command('mensalidades:gerar')->monthly();
        // CALCULA JUROS TODOS DIAS
        $schedule->command('mensalidades:juros')->daily();

        // GERA PARCELAS TODO MÊS DAS COTAS
        $schedule->command('mensalidadescota:gerar')->monthly();
        // CALCULA JUROS TODOS DIAS A COTA
        $schedule->command('mensalidadescota:juros')->daily();

        $schedule->call(function () {
            $status = SystemLock::first();

            if (!$status) {
                // Cria o registro inicial
                SystemLock::create([
                    'locked' => false,
                    'last_attempt_at' => now()
                ]);
                return;
            }

            $lastCheck = $status->last_attempt_at ?? $status->created_at;
            if (now()->diffInMonths($lastCheck) >= 2) {
                $status->update([
                    'locked' => true,
                    'last_attempt_at' => now()
                ]);
            }
        })->daily();

        // $schedule->command('inspire')->hourly();
        $schedule->command('audit:clear-old')->daily();
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
