<?php
namespace Bee\Logger\Adapter;

/**
 * Class SeasLog
 *
 * @package Bee\Logger\Adapter
 */
class SeasLog
{
    /**
     * SeasLog constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['base_dir'])) {
            \SeasLog::setBasePath($config['base_dir']);
        }
        if (isset($config['folder_name'])) {
            \SeasLog::setLogger($config['folder_name']);
        }
        if (isset($config['template'])) {
            ini_set('seaslog.default_template', $config['template']);
        }
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function emergency($message, array $context = [], string $module = '')
    {
        \SeasLog::log(SEASLOG_EMERGENCY, $message, $context, $module);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function alert($message, array $context = [], string $module = '')
    {
        \SeasLog::log(SEASLOG_ALERT, $message, $context, $module);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function critical($message, array $context = [], string $module = '')
    {
        \SeasLog::log(SEASLOG_CRITICAL, $message, $context, $module);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function error($message, array $context = [], string $module = '')
    {
        \SeasLog::log(SEASLOG_ERROR, $message, $context, $module);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function warning($message, array $context = [], string $module = '')
    {
        \SeasLog::log(SEASLOG_WARNING, $message, $context, $module);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function notice($message, array $context =[], string $module = '')
    {
        \SeasLog::log(SEASLOG_NOTICE, $message, $context, $module);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function info($message, array $context = [], string $module = '')
    {
        \SeasLog::log(SEASLOG_INFO, $message, $context, $module);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function debug($message, array $context = [], string $module = '')
    {
        \SeasLog::log(SEASLOG_DEBUG, $message, $context, $module);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @param string $module
     * @return void
     */
    public function log($level, $message, array $context = [], string $module = '')
    {
        \SeasLog::log($level, $message, $context, $module);
    }
}
