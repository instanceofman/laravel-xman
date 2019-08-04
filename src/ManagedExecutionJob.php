<?php


namespace Isofman\LaravelXman;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LogicException;

/**
 * Class ManagedExecutionJob
 * @package Isofman\LaravelXman
 */
abstract class ManagedExecutionJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $tries = 1;
    /**
     * @var int
     */
    public $timeout = 999999;

    /**
     * @var string
     */
    protected $jobName;

    /**
     * @var ExecutionManager
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $shouldRun = true;

    /**
     * ManagedExecutionJob constructor.
     * @param array $args
     */
    public function __construct($args = [])
    {
        $this->logger = new ExecutionLogger($this->jobName, $args);
    }

    /**
     * @throws LogicException
     */
    protected function beforeExecute()
    {
        if (empty($this->jobName))
            throw new LogicException("Unknown task name");

        $this->logger->start();

        if (! app('xman')->checkin($this->jobName)) {
            $this->shouldRun = false;
        } else {
            $this->logger->appendLog("Run " . $this->jobName);
        }
    }

    protected function afterExecute()
    {
        $this->logger->appendLog("Finished " . $this->jobName);
        $this->logger->complete();
        app('xman')->checkout($this->jobName);
    }

    protected function existWithoutExecute()
    {
        $this->logger->appendLog("The process already running. Nothing to do.");
        $this->logger->complete();
    }

    /**
     * @throws LogicException
     */
    public function handle()
    {
        $this->beforeExecute();

        if ($this->shouldRun) {
            $this->execute();
            $this->afterExecute();
        } else {
            $this->existWithoutExecute();
        }
    }

    /**
     * @return mixed
     */
    public abstract function execute();
}
