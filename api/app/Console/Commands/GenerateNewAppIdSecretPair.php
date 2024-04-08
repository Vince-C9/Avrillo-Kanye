<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\App as APIApp;

class GenerateNewAppIdSecretPair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Generating new application keys...");
        $name= $this->argument('name');
        $apiApp = APIApp::generate($name);
        $appKey = $apiApp->app_access_id;
        $token = $apiApp->accessToken->first()->secret_token;
        $this->info("New key generated for '$name'");
        $this->info('-----');
        $this->info(' ');
        $this->info('App Key: '.$appKey);
        $this->info('App Secret: '. $token);
        $this->info('-----');

        return 1;

    }
}
