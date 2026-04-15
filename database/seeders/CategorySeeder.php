<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * أقسام تجريبية (يمكن للأدمن حذفها أو إضافة أقسام من لوحة المدير).
     */
    public function run(): void
    {
        $items = [
            ['name' => 'ذكاء اصطناعي', 'slug' => 'ai'],
            ['name' => 'تطوير الويب', 'slug' => 'web-development'],
            ['name' => 'تسويق رقمي', 'slug' => 'digital-marketing'],
        ];

        foreach ($items as $item) {
            Category::firstOrCreate(
                ['slug' => $item['slug']],
                ['name' => $item['name']]
            );
        }
    }
}
