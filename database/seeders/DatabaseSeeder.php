<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Board;
use App\Models\Column;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)
            ->has(
                Board::factory(1)
                    ->has(
                        Column::factory(3)
                            ->has(
                                Task::factory(5)
                                    ->has(
                                        Subtask::factory(3)
                                    )
                            )
                    )
            )
            ->create();
    }
}
