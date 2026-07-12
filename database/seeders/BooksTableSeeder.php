<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'isbn' => '978-4-8156-3594-7',
                'title' => '改訂版  これからはじめるReact実践入門',
                'price' => '4455',
                'publisher' => 'SBクリエイティブ',
                'published' => '2025-09-12',
                'sample' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'isbn' => '978-4-297-14598-9',
                'title' => 'Railsアプリケーションプログラミング',
                'price' => 3960,
                'publisher' => '技術評論社',
                'published' => '2024-12-07',
                'sample' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
