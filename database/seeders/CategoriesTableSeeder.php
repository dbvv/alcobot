<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Виски',
            "Водка",
            "Пиво",
            "Вино",
            "Коньяк",
            "Ром",
        ];

        foreach ($data as $d) {
            Category::create([
                'name' => $d,
            ]);
        }
    }
}
