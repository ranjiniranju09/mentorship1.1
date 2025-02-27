<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            [
                'id' => 1,
                'name' => 'Problem Solving',
                'description' => 'To equip young individuals with effective problem-solving skills as they transition from college to a professional environment. TEST',
                'objective' => 'The transition from academic life to the workplace can be both exciting and daunting. As students enter new environments, they encounter different expectations, challenges, and perspectives. Problem-solving skills become essential in navigating this shift. This module is designed to help mentees understand the importance of problem-solving, learn structured techniques, and appreciate diverse perspectives. These skills will enable them to adapt to new situations, build confidence, and approach challenges constructively.',
                'created_at' => '2024-11-10 04:31:51',
                'updated_at' => '2024-11-10 04:58:41',
            ],
            [
                'id' => 2,
                'name' => 'Decision Making',
                'description' => 'Mentor’s Closing Note: Encourage mentees to take the time to think through choices carefully, even for seemingly small decisions. The more they practice structured decision-making, the easier it becomes to handle complex choices. Key Takeaways: Mentees will leave with a toolkit for thoughtful, structured decision-making. They will understand the importance of assessing options and predicting potential outcomes. The module fosters self-awareness, empathy, and the ability to foresee the broader impact of their actions.',
                'objective' => 'This module focuses on developing informed and effective decision-making skills. The goal is to equip mentees with a structured approach to evaluate options and understand the potential consequences of their choices, fostering a habit of thoughtful decision-making.',
                'created_at' => '2024-11-27 17:42:47',
                'updated_at' => '2024-11-27 18:54:25',
            ],
            [
                'id' => 3,
                'name' => 'Time Management',
                'description' => 'To help students develop practical strategies for managing time effectively, balancing academic and personal commitments, and improving productivity.',
                'objective' => 'Time management is a foundational skill that influences academic success, career advancement, and personal well-being. This module introduces students to effective time management strategies, highlights common time-wasting habits, and includes techniques to structure their day more effectively. By the end of this module, students will be equipped to prioritize tasks, reduce procrastination, and create realistic schedules that align with their goals.',
                'created_at' => '2024-11-28 08:53:50',
                'updated_at' => '2024-11-28 08:53:50',
            ],
            [
                'id' => 4,
                'name' => 'Stress Management',
                'description' => '1. Define stress, its types, and its effects on well-being. 2. Recognize common stressors and symptoms in daily life. 3. Practice mindfulness and breathing techniques to reduce stress. 4. Develop strategies to prevent stress and improve mental health. 5. Build a personalized stress management action plan for sustainable resilience.',
                'objective' => 'This module introduces the fundamentals of stress, its impact on mental and physical health, and practical strategies for managing it effectively. Participants will explore the science behind stress responses, understand personal triggers, and learn evidence-based techniques to cope with stress in everyday life. The module includes mindfulness practices, interactive activities, case studies, and a focus on building resilience.',
                'created_at' => '2024-12-18 09:56:06',
                'updated_at' => '2024-12-18 09:56:06',
            ],
            [
                'id' => 5,
                'name' => 'Effective Communication and Interpersonal Relationships',
                'description' => '# Define and explore the concept of communication and its significance. # Understand the foundations and importance of interpersonal relationships. # Differentiate between communication styles and identify one\'s dominant style. # Develop confidence in communication through “I-statements.” # Learn the role of apologizing in taking responsibility.',
                'objective' => 'The Effective Communication and Interpersonal Relationships module empowers participants to master essential communication skills, understand interpersonal dynamics, and build healthier relationships. This training explores various communication styles, introduces powerful techniques like "I-statements" for constructive expression, and encourages accountability through responsible communication.',
                'created_at' => '2024-12-19 01:35:26',
                'updated_at' => '2024-12-19 01:38:08',
            ],
            [
                'id' => 6,
                'name' => 'Conflict Management and Team Building',
                'description' => 'Conflict is a natural part of human relationships. When managed well, it can promote understanding and strengthen bonds; when poorly managed, it can harm relationships and productivity. This module will guide mentees in understanding the nature of conflict, exploring various conflict resolution techniques, and fostering a collaborative team environment. Through interactive exercises, mentees will learn practical strategies for managing conflicts and building positive team dynamics.',
                'objective' => '',
                'created_at' => '2024-12-19 05:53:40',
                'updated_at' => '2024-12-19 05:53:40',
            ],
        ]);
    }
}
