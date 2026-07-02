<?php

namespace App\Logging;

use Monolog\Logger;

class DatabaseLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger('database');
        
        // You can set level via config or default to debug
        $level = $config['level'] ?? Logger::DEBUG;

        $handler = new DatabaseHandler($level);
        
        $logger->pushHandler($handler);

        return $logger;
    }
}
