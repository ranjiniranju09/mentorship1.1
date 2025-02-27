<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tests')->insert([
            [1, 'Defining a problem', 1, Carbon::create('2024', '11', '17', '21', '52', '02'), Carbon::create('2024', '11', '19', '08', '10', '30'), null, 5, '1'],
            [2, 'Cause of Problems', 1, Carbon::create('2024', '11', '27', '09', '39', '14'), Carbon::create('2024', '11', '27', '09', '41', '12'), null, 6, '1'],
            [3, 'Strategies for Problem Solving', 1, Carbon::create('2024', '11', '27', '16', '47', '03'), Carbon::create('2024', '11', '27', '16', '47', '03'), null, 7, '1'],
            [4, 'Future Measures', 1, Carbon::create('2024', '11', '27', '16', '47', '51'), Carbon::create('2024', '11', '27', '16', '47', '51'), null, 8, '1'],
            [5, 'Example', 1, Carbon::create('2024', '11', '27', '18', '35', '25'), Carbon::create('2024', '12', '18', '02', '38', '44'), Carbon::create('2024', '12', '18', '02', '38', '44'), null, 1],
            [6, 'End of Module - Problem Solving Quiz', 1, Carbon::create('2024', '11', '27', '18', '39', '15'), Carbon::create('2024', '12', '18', '03', '14', '51'), null, 13, '1'],
            [7, 'Ripple Effect Of Decision Making', 1, Carbon::create('2024', '12', '18', '05', '50', '39'), Carbon::create('2024', '12', '18', '05', '51', '22'), Carbon::create('2024', '12', '18', '05', '51', '22'), 11, '2'],
            [8, 'End of Module - Decision Making', 1, Carbon::create('2024', '12', '18', '06', '20', '09'), Carbon::create('2024', '12', '18', '06', '20', '09'), null, 14, '2'],
            [9, 'Questions for Reflection', 1, Carbon::create('2024', '12', '18', '07', '57', '02'), Carbon::create('2024', '12', '18', '07', '57', '02'), null, 16, '3'],
            [10, 'The Psychology of Procrastination', 1, Carbon::create('2024', '12', '18', '08', '08', '14'), Carbon::create('2024', '12', '18', '08', '08', '14'), null, 17, '3'],
            [11, 'Developing a Personalized Schedule', 1, Carbon::create('2024', '12', '18', '08', '42', '17'), Carbon::create('2024', '12', '18', '08', '42', '17'), null, 18, '3'],
            [12, 'Self-Reflection on Stress Triggers', 1, Carbon::create('2024', '12', '18', '10', '05', '11'), Carbon::create('2024', '12', '18', '10', '05', '11'), null, 20, '4'],
            [13, 'Case Study', 1, Carbon::create('2024', '12', '18', '10', '16', '37'), Carbon::create('2024', '12', '18', '10', '16', '37'), null, 21, '4'],
            [14, 'Case Study Reflection', 1, Carbon::create('2024', '12', '18', '13', '28', '41'), Carbon::create('2024', '12', '18', '13', '28', '41'), null, 23, '4'],
            [15, 'Future Measures and Personalized Action Plan', 1, Carbon::create('2024', '12', '18', '13', '38', '32'), Carbon::create('2024', '12', '18', '13', '38', '32'), null, 24, '4'],
            [16, 'Reflection Questions', 1, Carbon::create('2024', '12', '18', '13', '46', '09'), Carbon::create('2024', '12', '18', '13', '46', '09'), null, 25, '3'],
            [17, 'Assessment Quiz', 1, Carbon::create('2024', '12', '18', '13', '51', '11'), Carbon::create('2024', '12', '18', '13', '51', '11'), null, 26, '3'],
            [18, 'End of module - Time Management', 1, Carbon::create('2024', '12', '18', '14', '12', '42'), Carbon::create('2024', '12', '18', '14', '12', '42'), null, 29, '3'],
            [19, 'End Of Module - Stress Management', 1, Carbon::create('2024', '12', '18', '15', '11', '46'), Carbon::create('2024', '12', '18', '15', '11', '46'), null, 30, '4'],
            [20, 'Activity', 1, Carbon::create('2024', '12', '19', '01', '58', '31'), Carbon::create('2024', '12', '19', '01', '58', '31'), null, 33, '5'],
            [21, 'Role Play Activity', 1, Carbon::create('2024', '12', '19', '02', '23', '05'), Carbon::create('2024', '12', '19', '02', '24', '32'), Carbon::create('2024', '12', '19', '02', '24', '32'), 34, '5'],
            [22, 'End Of Module Quiz', 1, Carbon::create('2024', '12', '19', '03', '31', '30'), Carbon::create('2024', '12', '19', '03', '31', '30'), null, 39, '5'],
            [23, 'Discussion Questions', 1, Carbon::create('2024', '12', '19', '07', '08', '42'), Carbon::create('2024', '12', '19', '07', '08', '42'), null, 40, '6'],
            [24, 'Discussion - Different ways to Handle Conflicts', 1, Carbon::create('2024', '12', '19', '07', '52', '24'), Carbon::create('2024', '12', '19', '07', '52', '24'), null, 42, '6'],
            [25, 'Discussion points', 1, Carbon::create('2024', '12', '19', '08', '44', '11'), Carbon::create('2024', '12', '19', '08', '44', '11'), null, 44, '6'],
            [26, 'Reflection Discussion', 1, Carbon::create('2024', '12', '19', '09', '01', '21'), Carbon::create('2024', '12', '19', '09', '01', '21'), null, 45, '6'],
            [27, 'End Of Module Quiz', 1, Carbon::create('2024', '12', '19', '09', '07', '52'), Carbon::create('2024', '12', '19', '09', '07', '52'), null, 46, '6'],
        ]);
    }
}
