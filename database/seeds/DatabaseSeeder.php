<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeStarted = microtime(true);

        $this->command->info('Seeding users into the database...');
        factory(App\User::class, 50)->create();

        $this->command->info('Seeding groups into the database and attaching them to users...');
        factory(App\Group::class, 25)->create()
            ->each(function ($group) {
                for ($i = 0; $i < rand(0, 10); $i++)
                    $group->members()->syncWithoutDetaching(rand(1, \App\User::count()));
            });

        $this->command->info('Seeding folders into the database and attaching them to groups...');
        factory(App\Folder::class, 150)->create()
            ->each(function ($folder) {
                for ($i = 0; $i < rand(0, 1); $i++)
                    $folder->groups()->syncWithoutDetaching(rand(1, \App\Group::count()));
            });

        foreach(App\Folder::all() as $folder) {
            for ($i = 0; $i < rand(0, 1); $i++) {
                $folder->parent_id = rand(1, \App\Folder::count());
                $folder->update();
            }
        }

        $this->command->info('Seeding documents into the database and attaching them to groups...');
        factory(App\Document::class, 250)->create()
            ->each(function ($document) {
                for($i = 0; $i < rand(0, 2); $i++)
                    $document->groups()->syncWithoutDetaching(rand(1, \App\Group::count()));
            });

        $this->command->info('Seeding keywords into the database and attaching them to documents...');
        factory(App\Keyword::class, 25)->create()
            ->each(function ($keyword) {
                for($i = 0; $i < rand(0, 5); $i++)
                    $keyword->documents()->syncWithoutDetaching(rand(1, \App\Document::count()));
            });

        $this->command->info('Seeding comments into the database...');
        factory(App\Comment::class, 500)->create();

        $this->command->info('Importing records into index...');
        Artisan::call('scout:import', [
            'model' => 'App\User',
        ]);

        $timeSpent = microtime(true) - $timeStarted;
        $this->command->comment('Seeding lasted ' . round($timeSpent / 60, 2) . ' minutes.');
    }
}
