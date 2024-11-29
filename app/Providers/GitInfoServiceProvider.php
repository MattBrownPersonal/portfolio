<?php

namespace App\Providers;

use App\Services\GitInfoService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class GitInfoServiceProvider extends ServiceProvider
{
    /**
     * Register GitInfoService.
     *
     * @return void
     */
    public function register()
    {
    }

    public function boot()
    {
        $filename = base_path().'/version.yml';
        if (file_exists($filename)) {
            $yaml = yaml_parse_file($filename);

            View::share('version', $yaml['commit_short_hash'].' ('.$yaml['commit_timestamp'].')');
            View::share('commit_hash', $yaml['commit_hash']);
            View::share('commit_short_hash', $yaml['commit_short_hash']);
            View::share('commit_timestamp', $yaml['commit_timestamp']);
        } else {
            View::share('version', 'unknown');
            View::share('commit_hash', 'unknown');
            View::share('commit_short_hash', 'unknown');
            View::share('commit_timestamp', 'unknown');
        }
    }
}
