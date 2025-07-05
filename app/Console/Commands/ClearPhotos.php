<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class ClearPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all photos from database and storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing all photos...');
        
        // Delete all photo records from database
        $photoCount = Photo::count();
        Photo::truncate();
        
        // Clear storage directories
        if (Storage::disk('public')->exists('restaurants')) {
            Storage::disk('public')->deleteDirectory('restaurants');
            Storage::disk('public')->makeDirectory('restaurants');
        }
        
        $this->info("Cleared {$photoCount} photos from database and storage.");
        
        return 0;
    }
}