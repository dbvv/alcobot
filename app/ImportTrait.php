<?php

namespace App;

use App\Models\Product;
use App\Models\Category;
use PhpOffice\PhpSpreadsheet\IOFactory;

trait ImportTrait {
    public function importProducts($file) {
        $spreadsheet = IOFactory::load($file);
        $ws = $spreadsheet->getActiveSheet();
        $rows = $ws->toArray();
        $categories = [];

        /**
         * [
         *  [0] => "Наименование",
         *  [1] => "Цена",
         *  [2] => "Категория",
         *  [3] => "Описание"
         * ]
         */
        foreach ($rows as $row) {
            // skip first row
            if ($row[0] === 'Наименование') {
                continue;
            }

            $product = Product::where('name', $row[0])->first();

            if (!$product) {
                $data = [
                    'name' => $row[0],
                    'price' => (int) $row[1],
                ];
                if ($row[3]) {
                    $data['description'] = $row[3];
                }
                if (!isset($categories[$row[2]])) {
                    $category = Category::where('name', $row[2])->first();
                    if (!$category) {
                        $category = Category::create([
                            'name' => $row[2],
                        ]);
                    }
                    $data['category_id'] = $category->id;
                    $categories[$row[2]] = $category->id;
                } else {
                    $data['category_id'] = $categories[$row[2]];
                }
                $product = Product::create($data);
            } else {
                $product->price = (int) $row[1];
                $product->save();
            }
        }
    }
}
