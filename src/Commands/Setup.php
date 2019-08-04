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

        if ($exists = Schema::hasTable($table)) {
            return;
        }

        $this->runMigration($table);
    }

    protected function runMigration($table)
    {
        Schema::create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('command', 150)->unique();
            $table->string('args', 150)->default('');
            $table->bigInteger('start');
            $table->bigInteger('end');
            $table->text('logs');
            $table->string('status', 30);
            $table->timestamps();
        });
    }
}