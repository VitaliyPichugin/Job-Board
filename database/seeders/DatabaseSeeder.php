<?php declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $tags = Tag::factory(30)->create();
        \App\Models\JobVacancy::factory(10)->create()->each(function ($jobVacancy) use ($tags) {
            $jobVacancy->tags()->attach($tags->random(rand(2, 5)));
        });
    }
}
