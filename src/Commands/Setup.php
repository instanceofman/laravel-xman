<?php


namespace Isofman\LaravelXman\Commands;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Command;

class Setup extends Command
{
    protected $signature = 'xman:setup';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $table = config('xman.table');

        if(!$table) {
            $this->error("Please run `php artisan vendor:publish` to copy config file before setup.");
            return false;
        }

        if ($exists = Schema::hasTable($table)) {
            return $this->success();
        }

        $this->runMigration($table);

        return $this->success();
    }

    protected function success()
    {
        $this->info('Setup xman successfully!');
        return true;
    }

    protected function runMigration($table)
    {
        Schema::create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('command', 150)->unique();
            $table->string('args', 150)->default('');
            $table->bigInteger('start')->nullable();
            $table->bigInteger('end')->nullable();
            $table->text('logs')->nullable();
            $table->string('status', 30);
            $table->string('progress', 30)->nullable();
            $table->string('total', 30)->nullable();
            $table->timestamps();
        });
    }
}