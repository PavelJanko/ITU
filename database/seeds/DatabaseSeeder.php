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
        $timeStarted = \Carbon\Carbon::now();

        $this->command->info('Seeduji uživatele...');
        factory(App\User::class, 50)->create();

        $this->command->info('Seeduji skupiny a připojuji je k uživatelům...');
        factory(App\Group::class, 25)->create()
            ->each(function ($group) {
                for ($i = 0; $i < rand(0, 10); $i++)
                    $group->members()->syncWithoutDetaching(rand(1, \App\User::count()));
            });

        $this->command->info('Seeduji složky a připojuji je ke skupinám...');
        factory(App\Folder::class, 150)->create()
            ->each(function ($folder) {
                for ($i = 0; $i < rand(0, 1); $i++) {
                    $folderOwner = $folder->owner;
                    $groupId = rand(1, \App\Group::count());

                    if(!$folderOwner->groups->pluck('id')->contains($groupId))
                        $folderOwner->groups()->syncWithoutDetaching($groupId);

                    $folder->groups()->syncWithoutDetaching($groupId);
                }
            });

        foreach(App\Folder::all() as $folder) {
            for ($i = 0; $i < rand(0, 1); $i++) {
                if($folder->owner->folders->count()) {
                    $folder->parent_id = $folder->owner->folders->count() > 1 ?
                        $folder->owner->folders->where('id', '<>', $folder->id)->pluck('id')->random() : NULL;

                    if(!$folder->owner->folders->where('parent_id', '<>', NULL)->count()) {
                        $rootFolder = $folder->owner->folders->first();
                        $rootFolder->parent_id = NULL;
                        $rootFolder->update();
                    }

                    $folder->update();
                }
            }
        }

        $this->command->info('Seeduji dokumenty a připojuji je ke skupinám...');
        factory(App\Document::class, 250)->create()
            ->each(function ($document) {
                $document->copyMedia('storage/seeding_files/file' . rand(1, 10) . '.dat')->toMediaCollection();

                for($i = 0; $i < rand(0, 2); $i++) {
                    $documentOwner = $document->owner;
                    $groupId = rand(1, \App\Group::count());

                    if(!$documentOwner->groups->pluck('id')->contains($groupId))
                        $documentOwner->groups()->syncWithoutDetaching($groupId);

                    $document->groups()->syncWithoutDetaching($groupId);
                }
            });

        $this->command->info('Seeduji klíčová slova a připojuji je k dokumentům...');
        factory(App\Keyword::class, 25)->create()
            ->each(function ($keyword) {
                for($i = 0; $i < rand(0, 5); $i++)
                    $keyword->documents()->syncWithoutDetaching(rand(1, \App\Document::count()));
            });

        $this->command->info('Seeduji komentáře...');
        factory(App\Comment::class, 500)->create();

        $this->command->info('Importuji záznamy do indexu...');
        Artisan::call('scout:import', [
            'model' => 'App\Keyword',
        ]);

        $timeSpent = \Carbon\Carbon::now()->diffForHumans($timeStarted, true);
        $this->command->comment('Hotovo! Seedování trvalo ' . $timeSpent . '.');
    }
}
