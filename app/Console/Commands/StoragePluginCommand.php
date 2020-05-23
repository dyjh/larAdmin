<?php

namespace Illuminate\Foundation\Console;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class StoragePluginCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:plugins {--relative : Create the symbolic link using relative paths}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the symbolic plugins configured for the application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->plugins() as $plugin => $target) {
            if (file_exists($plugin)) {
                $this->error("The [$plugin] link already exists.");
            } else {
                if ($this->option('relative')) {
                    $target = $this->getRelativeTarget($plugin, $target);
                }

                $this->laravel->make('files')->link($target, $plugin);

                $this->info("The [$plugin] link has been connected to [$target].");
            }
        }

        $this->info('The links have been created.');
    }

    /**
     * Get the symbolic links that are configured for the application.
     *
     * @return array
     */
    protected function plugins()
    {
        return $this->laravel['config']['filesystems.plugins'] ??
               [public_path('plugins') => base_path('plugins')];
    }

    /**
     * Get the relative path to the target.
     *
     * @param  string  $plugin
     * @param  string  $target
     * @return string
     */
    protected function getRelativeTarget($plugin, $target)
    {
        if (! class_exists(SymfonyFilesystem::class)) {
            throw new Exception('Please install the symfony/filesystem Composer package to create relative links.');
        }

        return (new SymfonyFilesystem)->makePathRelative($target, dirname($plugin));
    }
}
