<?php

namespace Woolf\Carter;

use Illuminate\Console\Command;

class CarterTableCommand extends Command
{

    protected $name = 'carter:table';

    protected $description = 'Create a migration for the Carter database columns';

    public function fire()
    {
        file_put_contents($this->getMigrationPath(), $this->getMigrationStub());

        $this->info('Migration created successfully!');

        $this->laravel['composer']->dumpAutoloads();
    }

    protected function getMigrationPath()
    {
        $name = 'add_carter_columns';

        $path = $this->laravel['path.database'] . '/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }

    protected function getMigrationStub()
    {
        return file_get_contents(__DIR__ . '/stubs/migration.stub');
    }
}