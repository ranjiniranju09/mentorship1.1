<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class MasterSessionExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Retrieve the data to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
{
    return DB::table('mentors')
        // Join mappings to include only mapped mentors
        ->join('mappings', 'mentors.id', '=', 'mappings.mentorname_id')

        // Join mentees through mappings
        ->join('mentees', 'mappings.menteename_id', '=', 'mentees.id')

        // Join module completion tracker using mentee ID
        ->leftJoin('module_completion_tracker', 'mentees.id', '=', 'module_completion_tracker.mentee_id')

        // Join modules
        ->leftJoin('modules', 'module_completion_tracker.module_id', '=', 'modules.id')

        // Join sessions where 'done' is true
        ->leftJoin('sessions', function ($join) {
            $join->on('mentors.id', '=', 'sessions.mentorname_id')
                ->where('sessions.done', '=', 1);
        })

        ->select(
            'mentors.name as mentor_name',

            // Completed modules by mapped mentees
            DB::raw('(
                SELECT GROUP_CONCAT(DISTINCT m.name)
                FROM modules m
                JOIN module_completion_tracker mct ON m.id = mct.module_id
                WHERE mct.mentee_id IN (
                    SELECT menteename_id
                    FROM mappings
                    WHERE mentorname_id = mentors.id
                )
            ) as completed_modules_names'),

            // Pending modules for the mapped mentees
            DB::raw('(
                SELECT GROUP_CONCAT(name)
                FROM modules
                WHERE id NOT IN (
                    SELECT module_id
                    FROM module_completion_tracker
                    WHERE mentee_id IN (
                        SELECT menteename_id
                        FROM mappings
                        WHERE mentorname_id = mentors.id
                    )
                )
            ) as pending_modules_names'),

            // Total session hours where 'done' is 1
            DB::raw('COALESCE(SUM(sessions.session_duration_minutes) / 60, 0) as total_hours_engaged')
        )

        ->groupBy('mentors.id', 'mentors.name')
        ->get();
}


    /**
     * Define the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Sl. No.',
            'Mentor Name',
            'Completed Module Names',
            'Pending Module Names',
            'Total Hours Engaged (Hours)'
        ];
    }

    /**
     * Map the data for export.
     *
     * @param $row
     * @return array
     */
    public function map($row): array
    {
        static $index = 0;
        return [
            ++$index,
            $row->mentor_name,
            $row->completed_modules_names ?? 'None',
            $row->pending_modules_names ?? 'None',
            round($row->total_hours_engaged, 2) . ' hours'
        ];
    }
}
