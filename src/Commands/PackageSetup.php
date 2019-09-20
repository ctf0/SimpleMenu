<?php

namespace ctf0\SimpleMenu\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class PackageSetup extends Command
{
    protected $file;
    protected $signature = 'sm:setup';
    protected $description = 'setup package routes & assets compiling';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->file = app('files');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // routes
        $route_file = base_path('routes/web.php');
        $search = 'SimpleMenu';

        if ($this->checkExist($route_file, $search)) {
            $data = "\n// SimpleMenu\nSimpleMenu::menuRoutes();";

            $this->file->append($route_file, $data);
        }

        // mix
        $mix_file = base_path('webpack.mix.js');
        $search = 'SimpleMenu';

        if ($this->checkExist($mix_file, $search)) {
            $data = "\n// SimpleMenu\nmix.sass('resources/assets/vendor/SimpleMenu/sass/style.scss', 'public/assets/vendor/SimpleMenu/style.css')";

            $this->file->append($mix_file, $data);
        }

        $this->info('All Done');
    }

    /**
     * [checkExist description].
     *
     * @param [type] $file   [description]
     * @param [type] $search [description]
     *
     * @return [type] [description]
     */
    protected function checkExist($file, $search)
    {
        return $this->file->exists($file) && !Str::contains($this->file->get($file), $search);
    }
}