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
     * @var array
     */
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('xman.table');
    }
}
