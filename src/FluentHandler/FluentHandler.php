<?php

namespace FluentHandler;

use Fluent\Logger\FluentLogger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Class FluentHandler
 *
 * @package FluentHandler\FluentHandler
 */
class FluentHandler extends AbstractProcessingHandler
{
    /**
     * @var FluentLogger
     */
    protected $logger;

    /**
     * Initialize Handler
     *
     * @param FluentLogger $logger
     * @param bool|string $host
     * @param int $port
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(
        $logger = null,
        $host   = FluentLogger::DEFAULT_ADDRESS,
        $port   = FluentLogger::DEFAULT_LISTEN_PORT,
        $level  = Logger::DEBUG,
        $bubble = true
    )
    {
        parent::__construct($level, $bubble);

        if (is_null($logger)) {
            $logger = new FluentLogger($host, $port);
        }

        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function write(array $record)
    {
        $record['level'] = Logger::getLevelName($record['level']);
        $tag  = $record['channel'] . '.' . $record['message'];
        $this->logger->post($tag, $record);
    }
}
