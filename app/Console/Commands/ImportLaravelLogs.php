<?php

namespace App\Console\Commands;

use App\Models\ApplicationLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportLaravelLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:import-file {--file= : The path to the log file (defaults to storage/logs/laravel.log)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Laravel file logs into the application_logs database table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->option('file') ?: storage_path('logs/laravel.log');

        if (!File::exists($file)) {
            $this->error("Log file not found at: {$file}");
            return Command::FAILURE;
        }

        $this->info("Parsing log file: {$file}");

        $content = File::get($file);

        // Standard Monolog line format: "[Y-m-d H:i:s] env.LEVEL: Message {"context":...} {"extra":...}"
        // Regex to match the log structure
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] [a-zA-Z0-9_-]+\.([A-Z]+): (.*?)(?=\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] |$)/s';
        
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            $this->warn("No logs found or unable to parse the file format.");
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($matches));
        $bar->start();

        $imported = 0;

        foreach ($matches as $match) {
            $date = $match[1];
            $levelName = $match[2];
            $levelValue = $this->getLevelValue($levelName);
            $messageAndContext = trim($match[3]);
            
            // Try to extract JSON context at the end of the message if possible
            $message = $messageAndContext;
            $context = null;
            
            // A basic attempt to find JSON context at the end of the string
            // Monolog usually outputs {} {} at the end if there is context/extra
            if (preg_match('/ (\{.*?\}) (\{.*?\})$/', $messageAndContext, $jsonMatches)) {
                $contextStr = $jsonMatches[1];
                $message = substr($messageAndContext, 0, -strlen($jsonMatches[0]));
                $context = json_decode($contextStr, true);
            } elseif (preg_match('/ (\{.*?\})$/', $messageAndContext, $jsonMatches)) {
                $contextStr = $jsonMatches[1];
                $message = substr($messageAndContext, 0, -strlen($jsonMatches[0]));
                $context = json_decode($contextStr, true);
            }

            ApplicationLog::create([
                'level' => $levelValue,
                'level_name' => $levelName,
                'message' => $message,
                'context' => $context,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $imported++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully imported {$imported} log entries.");

        return Command::SUCCESS;
    }

    /**
     * Get the Monolog integer value for a given level name.
     */
    protected function getLevelValue(string $levelName): int
    {
        try {
            return \Monolog\Level::fromName($levelName)->value;
        } catch (\Throwable $e) {
            return 200; // Default to INFO
        }
    }
}
