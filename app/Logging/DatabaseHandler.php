<?php

namespace App\Logging;

use App\Models\ApplicationLog;
use Illuminate\Support\Facades\Schema;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DatabaseHandler extends AbstractProcessingHandler
{
    /**
     * @inheritDoc
     */
    protected function write(LogRecord $record): void
    {
        // Guard: silently skip if the table does not exist yet
        // (e.g., during `migrate:fresh` before this table has been created).
        try {
            if (! Schema::hasTable('application_logs')) {
                return;
            }

            ApplicationLog::create([
                'level'      => $record->level->value,
                'level_name' => $record->level->name,
                'message'    => $record->message,
                'context'    => $record->context,
                'extra'      => $record->extra,
            ]);
        } catch (\Throwable) {
            // Never let a logging failure bubble up and crash the application.
        }
    }
}
