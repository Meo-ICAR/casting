<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

class SendWhatsAppMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send
                            {--numbers=* : Array of phone numbers}
                            {--message= : Message to send}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp messages to multiple recipients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $numbers = $this->option('numbers');
        $message = $this->option('message') ?? 'Ciao! Ti contatto per il casting.';

        if (empty($numbers)) {
            $this->error('Nessun numero di telefono specificato!');
            return Command::FAILURE;
        }

        $urls = collect($numbers)
            ->filter()
            ->map(fn($number) => $this->generateWhatsAppUrl($number, $message))
            ->values()
            ->toArray();

        if (empty($urls)) {
            $this->error('Nessun numero di telefono valido trovato!');
            return Command::FAILURE;
        }

        $this->info("Apertura di " . count($urls) . " chat WhatsApp...");

        // Open each URL in a new tab
        foreach ($urls as $url) {
            if (PHP_OS_FAMILY === 'Windows') {
                pclose(popen('start "" "' . $url . '"', 'r'));
            } elseif (PHP_OS_FAMILY === 'Darwin') {
                exec('open "' . $url . '"');
            } else {
                exec('xdg-open "' . $url . '"');
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Generate WhatsApp URL for a given phone number and message
     */
    protected function generateWhatsAppUrl(string $phone, string $message): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $message = urlencode($message);

        return "https://wa.me/{$phone}?text={$message}";
    }

    /**
     * Helper method to use this from your code
     */
    public static function sendToNumbers(array $numbers, string $message = null): void
    {
        $command = new self();

        // Create a NullOutput instance to suppress console output when called programmatically
        $output = new \Symfony\Component\Console\Output\NullOutput();

        // Set the input with the provided options
        $input = new \Symfony\Component\Console\Input\ArrayInput([
            '--numbers' => $numbers,
            '--message' => $message ?? 'Ciao! Ti contatto per il casting.',
        ]);

        // Run the command with the null output
        $command->setLaravel(app());
        $command->run($input, $output);
    }
}
