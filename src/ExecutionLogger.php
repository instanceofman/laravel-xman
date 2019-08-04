<?php

namespace Isofman\LaravelXman;

/**
 * Class ExecutionLogger
 * @package Isofman\LaravelXman
 */
class ExecutionLogger
{
    const STATUS_STARTED = 'STARTED';

    const STATUS_COMPLETED = 'COMPLETED';

    // This status only apply for scanning task
    const STATUS_STOPPED = 'STOPPED';

    /**
     * @var string
     */
    protected $name;
    /**
     * @var array
     */
    protected $args;
    /**
     * @var
     */
    private $log;
    /**
     * @var array
     */
    private $logs;

    /**
     * ExecutionLogging constructor.
     * @param $name
     * @param array $args
     */
    public function __construct($name = "", array $args = [])
    {
        $this->name = $name;
        $this->args = $args;
        $this->logs = [];
    }

    /**
     * @return string
     */
    public static function getTimestamp()
    {
        $comps = explode(' ', microtime());
        return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
    }

    /**
     * @param ExecutionLog $log
     */
    public function recoverFromLog(ExecutionLog $log)
    {
        $this->log = $log;
        $this->name = $log->command;
        $this->args = json_decode($log->args, true);
    }

    /**
     * @param $msg
     * @param bool $save
     */
    public function appendLog($msg, $save = false)
    {
        $this->logs[] = $msg;
        if ($save) {
            $this->saveLog();
        }
    }

    /**
     * @param $total
     */
    public function setTotal($total)
    {
        $this->log->update(['total' => $total]);
    }

    /**
     * @param $progress
     */
    public function setProgress($progress)
    {
        $this->log->update(['progress' => $progress]);
    }

    /**
     *
     */
    public function start()
    {
        $log = ExecutionLog::create([
            'command' => $this->name,
            'args' => json_encode($this->args),
            'start' => $this->getTimestamp(),
            'log' => '',
            'status' => self::STATUS_STARTED
        ]);

        $this->log = $log;
    }

    /**
     *
     */
    public function stop()
    {
        $this->finishTheTask();
        $this->log->status = self::STATUS_STOPPED;
        $this->log->save();
    }

    /**
     *
     */
    public function complete()
    {
        $this->finishTheTask();
        $this->log->status = self::STATUS_COMPLETED;
        $this->log->save();
    }

    /**
     *
     */
    private function saveLog()
    {
        $str = implode("\n", $this->logs);
        $this->log->update(['log' => $str]);
    }

    /**
     *
     */
    private function finishTheTask()
    {
        $this->log->fill([
            'end' => $this->getTimestamp(),
            'log' => implode("\n", $this->logs)
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return array
     */
    public function getLogs(): array
    {
        return $this->logs;
    }
}
