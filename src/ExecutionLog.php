<?php
namespace Isofman\LaravelXman;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ExecutionLog
 * @package Isofman\LaravelXman
 */
class ExecutionLog extends Eloquent
{
    /**
     * @var string
     */
    protected $table = 'execution_logs';
    /**
     * @var array
     */
    protected $guarded = [];
}
