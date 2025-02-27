<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuizCompletionReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Updated query for mentee quiz progress, including MCQ question checks and pending quizzes logic
        return DB::table('modules')
            ->leftJoin('quiz_results', 'modules.id', '=', 'quiz_results.module_id')
            ->leftJoin('questions', 'quiz_results.tests_id', '=', 'questions.test_id')
            ->leftJoin('mappings', function ($join) {
                $join->on('mappings.mentorname_id', '=', 'quiz_results.user_id')
                    ->orOn('mappings.menteename_id', '=', 'quiz_results.user_id');
            })
            ->leftJoin('mentors', 'mappings.mentorname_id', '=', 'mentors.id')
            ->leftJoin('mentees', 'mappings.menteename_id', '=', 'mentees.id')
            ->select(
                'mentees.name as mentee_name',
                'modules.name as module_name',
                DB::raw('COUNT(DISTINCT questions.id) as total_questions'),
                DB::raw('COUNT(DISTINCT quiz_results.tests_id) as completed_quiz'),
                DB::raw('(1 - COUNT(DISTINCT quiz_results.tests_id)) as pending_quiz') // Assuming 1 test per module
            )
            ->where(function ($query) {
                $query->where('questions.mcq', 'yes')
                    ->orWhereNull('questions.id'); // Include modules without questions
            })
            ->groupBy('modules.id', 'modules.name', 'mentees.id', 'mentees.name')
            ->get()
            ->map(function ($row, $index) {
                return [
                    'Sl. No.' => $index + 1,
                    'Mentee Name' => $row->mentee_name,
                    'Module Name' => $row->module_name,
                    'Completed Quiz' => $row->completed_quiz,
                    'Pending Quiz' => $row->pending_quiz,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Sl. No.',
            'Mentee Name',
            'Module Name',
            'Completed Quiz',
            'Pending Quiz',
        ];
    }
}
