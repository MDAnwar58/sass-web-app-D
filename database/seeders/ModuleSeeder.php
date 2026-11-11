<?php

namespace Database\Seeders;

use App\Models\Core\Module;
use App\Models\Core\Plan;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $inventory = Module::firstOrCreate(['key' => 'inventory'], [
            'name' => 'Inventory System',
            'description' => 'Products, sales, purchases, stock management.',
            'monthly_price' => 9.00,
            'yearly_price' => 90.00,
        ]);

        $lms = Module::firstOrCreate(['key' => 'lms'], [
            'name' => 'Education / LMS',
            'description' => 'Courses, lessons, quizzes, enrollments.',
            'monthly_price' => 15.00,
            'yearly_price' => 150.00,
        ]);

        $inventoryPlan = Plan::firstOrCreate(['slug' => 'inventory'], [
            'name' => 'Inventory',
            'monthly_price' => 9.00,
            'yearly_price' => 90.00,
        ]);
        if ($inventoryPlan->modules()->where('module_id', $inventory->id)->doesntExist()) {
            $inventoryPlan->modules()->attach($inventory);
        }

        $educationPlan = Plan::firstOrCreate(['slug' => 'education'], [
            'name' => 'Education',
            'monthly_price' => 15.00,
            'yearly_price' => 150.00,
        ]);
        if ($educationPlan->modules()->where('module_id', $lms->id)->doesntExist()) {
            $educationPlan->modules()->attach($lms);
        }

        $businessPlan = Plan::firstOrCreate(['slug' => 'business'], [
            'name' => 'Business',
            'monthly_price' => 20.00,
            'yearly_price' => 200.00,
        ]);
        if ($businessPlan->modules()->whereIn('module_id', [$inventory->id, $lms->id])->count() < 2) {
            $businessPlan->modules()->sync([$inventory->id, $lms->id]);
        }
    }
}
