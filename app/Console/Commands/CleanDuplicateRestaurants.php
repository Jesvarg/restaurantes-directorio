<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class CleanDuplicateRestaurants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-duplicate-restaurants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean duplicate restaurants from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning duplicate restaurants...');
        
        // Find duplicates by name
        $duplicates = Restaurant::select('name', DB::raw('COUNT(*) as count'))
            ->groupBy('name')
            ->having('count', '>', 1)
            ->get();
            
        $totalDeleted = 0;
        
        foreach ($duplicates as $duplicate) {
            // Keep the first restaurant and delete the rest
            $restaurants = Restaurant::where('name', $duplicate->name)
                ->orderBy('id')
                ->get();
                
            // Skip the first one, delete the rest
            for ($i = 1; $i < $restaurants->count(); $i++) {
                $restaurants[$i]->delete();
                $totalDeleted++;
            }
        }
        
        $this->info("Deleted {$totalDeleted} duplicate restaurants.");
        return 0;
    }
}
