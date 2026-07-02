<?php

namespace App\Logging;

use App\Models\ApplicationLog;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DatabaseHandler extends AbstractProcessingHandler
{
    /**
     * @inheritDoc
     */
    protected function write(LogRecord $record): void
    {
        ApplicationLog::create([
            'level'      => $record->level->value,
            'level_name' => $record->level->name,
            'message'    => $record->message,
            'context'    => $record->context,
            'extra'      => $record->extra,
        ]);
    }
}
