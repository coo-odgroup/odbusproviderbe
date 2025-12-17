<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;
use DB;


class UpdateMinPriceForBus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:minPriceForBus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Min Price For Bus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
              $startTime = microtime(true);
              Log::info('UpdateMinPriceForBus started at: ' . now());

              $busPrices = DB::table('bus as b')
                                ->join('ticket_price as tp', 'tp.bus_id', '=', 'b.id')
                                ->leftJoin('bus_seats as bs', function ($join) {
                                    $join->on('bs.bus_id', '=', 'b.id')
                                        ->on('bs.ticket_price_id', '=', 'tp.id')
                                        ->where('bs.new_fare', '!=', 0.00);
                                })
                                ->select(
                                    'b.id as bus_id',
                                    DB::raw('MIN(
                                        CASE 
                                            WHEN bs.new_fare IS NOT NULL AND bs.new_fare != 0.00 
                                            THEN bs.new_fare 
                                            ELSE tp.base_seat_fare 
                                        END
                                    ) as min_fare')
                                )
                                ->groupBy('b.id')
                                ->get();

                if ($busPrices->isNotEmpty()) {

                        $caseSql = "CASE id ";
                        $ids = [];

                        foreach ($busPrices as $bus) {
                            $caseSql .= "WHEN {$bus->bus_id} THEN {$bus->min_fare} ";
                            $ids[] = $bus->bus_id;
                        }

                        $caseSql .= "END";

                        DB::table('bus')
                            ->whereIn('id', $ids)
                            ->update([
                                'min_price' => DB::raw($caseSql),
                                'min_price_updated_on' => date('Y-m-d H:i:s')
                            ]);
                }
           

        } catch (\Throwable $e) {
            Log::error('Error: ' . $e->getMessage());
        } finally {
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('UpdateMinPriceForBus ended at: ' . now());
            Log::info("Total execution time: {$executionTime} seconds");

            $this->info("Execution completed in {$executionTime} seconds");
        }
    }
}
