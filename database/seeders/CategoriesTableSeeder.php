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
        $categories = Category::all();

        if (count($category) > 0) {
            return;
        }

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
