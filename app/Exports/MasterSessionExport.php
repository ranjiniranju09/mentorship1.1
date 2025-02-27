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
    ->leftJoin('module_completion_tracker', 'mentors.user_id', '=', 'module_completion_tracker.user_id')
    ->leftJoin('modules', 'module_completion_tracker.module_id', '=', 'modules.id')
    ->leftJoin('sessions', function ($join) {
        $join->on('mentors.id', '=', 'sessions.mentorname_id')
             ->where('sessions.done', '=', 1); // Filter sessions where done = 1
    })
    ->select(
        'mentors.name as mentor_name',
        DB::raw('(SELECT GROUP_CONCAT(modules.name) 
                  FROM modules 
                  JOIN module_completion_tracker 
                  ON modules.id = module_completion_tracker.module_id 
                  WHERE module_completion_tracker.user_id = mentors.user_id) as completed_modules_names'),
        DB::raw('(SELECT GROUP_CONCAT(modules.name) 
                  FROM modules 
                  WHERE modules.id NOT IN 
                  (SELECT module_id 
                   FROM module_completion_tracker 
                   WHERE user_id = mentors.user_id)) as pending_modules_names'),
        DB::raw('COALESCE(SUM(sessions.session_duration_minutes) / 60, 0) as total_hours_engaged') // Aggregate only sessions where done = 1
    )
    ->groupBy('mentors.id')
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
