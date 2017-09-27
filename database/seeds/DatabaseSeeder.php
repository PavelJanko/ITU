<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Seeding groups into the database...');
        factory(App\Group::class, 5)->create();

        $this->command->info('Seeding users into the database...');
        factory(App\User::class, 25)->create();

        $this->command->info('Seeding documents into the database and attaching them to groups...');
        factory(App\Document::class, 100)->create()
            ->each(function ($document) {
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
    }
}
