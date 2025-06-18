<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuizCompletedReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = DB::select("
            SELECT 
                mentees.id AS mentee_id,
                mentees.name AS mentee_name,
                mentees.user_id AS mentee_user_id,

                (SELECT COUNT(*) FROM modules) AS total_modules,
                COUNT(DISTINCT quiz_results.module_id) AS completed_modules,
                ((SELECT COUNT(*) FROM modules) - COUNT(DISTINCT quiz_results.module_id)) AS pending_modules,

                GROUP_CONCAT(DISTINCT CONCAT(completed_modules.name, ' (Score: ', quiz_results.score, ')') SEPARATOR ', ') AS completed_module_scores,

                (
                    SELECT GROUP_CONCAT(DISTINCT m.name SEPARATOR ', ')
                    FROM modules m
                    WHERE m.id NOT IN (
                        SELECT module_id
                        FROM quiz_results
                        WHERE user_id = mentees.user_id
                    )
                ) AS pending_module_names,

                COALESCE(SUM(quiz_results.attempts), 0) AS total_attempts

            FROM mentees
            LEFT JOIN mappings ON mappings.menteename_id = mentees.id
            LEFT JOIN quiz_results ON quiz_results.user_id = mentees.user_id
            LEFT JOIN modules AS completed_modules ON completed_modules.id = quiz_results.module_id

            GROUP BY mentees.id, mentees.name, mentees.user_id
            ORDER BY mentees.name
        ");

        // Map data to expected export format
        return collect($data)->map(function ($row, $index) {
            return [
                'Sl. No.' => $index + 1,
                'Mentee Name' => $row->mentee_name,
                'Total Modules' => $row->total_modules,
                'Completed Modules (Count)' => $row->completed_modules,
                'Completed Modules (with Scores)' => $row->completed_module_scores ?: '—',
                'Pending Modules (Count)' => $row->pending_modules,
                'Pending Modules (Names)' => $row->pending_module_names ?: '—',
                'Total Attempts' => $row->total_attempts,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl. No.',
            'Mentee Name',
            'Total Modules',
            'Completed Modules (Count)',
            'Completed Modules (with Scores)',
            'Pending Modules (Count)',
            'Pending Modules (Names)',
            'Total Attempts',
        ];
    }
}
