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

        $this->command->info('Seeding groups into the database...');
        factory(App\Group::class, 5)->create();

        $this->command->info('Seeding users into the database...');
        factory(App\User::class, 25)->create();

        $this->command->info('Seeding documents into the database and attaching them to groups...');
        factory(App\Document::class, 100)->create()
            ->each(function ($document) {
                $document->addMediaFromUrl('http://itu.app/storage/placeimg_768_384_tech_' . rand(1, 10) . '.jpg')->toMediaCollection();

                for($i = 0; $i < rand(0, 2); $i++)
                    $document->groups()->syncWithoutDetaching(rand(1, \App\Group::count()));
            });

        $this->command->info('Seeding keywords into the database and attaching them to documents...');
        factory(App\Keyword::class, 50)->create()
            ->each(function ($keyword) {
                for($i = 0; $i < rand(1, 5); $i++)
                    $keyword->documents()->syncWithoutDetaching(rand(1, \App\Document::count()));
            });

        $this->command->info('Seeding comments into the database...');
        factory(App\Comment::class, 250)->create();

        $this->command->info('Importing records into index...');
        Artisan::call('scout:import', [
            'model' => 'App\Document',
        ]);

        $timeSpent = microtime(true) - $timeStarted;
        $this->command->comment('Seeding lasted ' . round($timeSpent/60, 2) . ' minutes.');
    }
}
