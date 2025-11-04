<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OpenExplorerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'explorer:open {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open Windows Explorer at specified path';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('path');
        
        $this->info("Original path: {$path}");

        // Convert to proper Windows path
        $windowsPath = $this->convertToWindowsPath($path);
        
        $this->info("Windows path: {$windowsPath}");

        // Execute Windows Explorer command
        if (PHP_OS_FAMILY === 'Windows') {
            return $this->openWindowsExplorer($windowsPath);
        } else {
            $this->error('This command only works on Windows systems.');
            $this->info('Current OS: ' . PHP_OS_FAMILY);
            return Command::FAILURE;
        }
    }

    /**
     * Convert path to Windows format
     */
    private function convertToWindowsPath(string $path): string
    {
        // Trim any whitespace
        $path = trim($path);
        
        // If it's already a proper Windows path with drive letter, return as is
        if (preg_match('/^[a-zA-Z]:\\\\/', $path)) {
            return $path;
        }
        
        // If it starts with just a drive letter like "C:", add backslash
        if (preg_match('/^[a-zA-Z]:$/', $path)) {
            return $path . '\\';
        }
        
        // If it starts with drive letter but wrong slashes like "C:", fix it
        if (preg_match('/^[a-zA-Z]:[\/\\\\]?/', $path)) {
            $path = str_replace('/', '\\', $path);
            // Ensure it ends with backslash if it's just a drive
            if (strlen($path) === 2) {
                $path .= '\\';
            }
            return $path;
        }
        
        // For relative paths, convert to absolute from Laravel root
        $absolutePath = base_path($path);
        $windowsPath = str_replace('/', '\\', $absolutePath);
        
        return $windowsPath;
    }

    /**
     * Open Windows Explorer
     */
    private function openWindowsExplorer(string $windowsPath): int
    {
        try {
            $this->info("Attempting to open: {$windowsPath}");
            
            // Check if path exists (optional, but helpful)
            if (!file_exists($windowsPath) && !is_dir($windowsPath)) {
                $this->warn("⚠️ Path does not exist, but attempting to open anyway: {$windowsPath}");
            }
            
            // Use different methods to open Explorer
            $methods = [
                // Method 1: Direct explorer command
                "explorer \"{$windowsPath}\"",
                
                // Method 2: Using start command
                "start explorer \"{$windowsPath}\"",
                
                // Method 3: Using cmd /c
                "cmd /c start explorer \"{$windowsPath}\"",
            ];
            
            foreach ($methods as $method) {
                $this->info("Trying: {$method}");
                
                $output = [];
                $returnCode = 0;
                
                $result = exec($method, $output, $returnCode);
                
                $this->info("Return code: {$returnCode}");
                $this->info("Output: " . implode(', ', $output));
                
                if ($returnCode === 0 || $returnCode === 1) {
                    // Return code 0 or 1 often means success for Windows commands
                    $this->info("✅ Successfully opened Windows Explorer using: {$method}");
                    Log::info("Windows Explorer opened successfully", [
                        'path' => $windowsPath,
                        'method' => $method
                    ]);
                    return Command::SUCCESS;
                }
                
                // Wait a bit before trying next method
                sleep(1);
            }
            
            $this->error("❌ All methods failed to open Windows Explorer");
            Log::error("All methods failed to open Windows Explorer", [
                'path' => $windowsPath,
                'final_return_code' => $returnCode
            ]);
            return Command::FAILURE;
            
        } catch (\Exception $e) {
            $this->error("❌ Exception opening Windows Explorer: " . $e->getMessage());
            Log::error("Exception opening Windows Explorer", [
                'path' => $windowsPath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
}