<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MentorwiseModuleReportExport implements FromCollection, WithHeadings
{
    public function collection()
{
    // SQL query to calculate mentor-wise session data
   $data = DB::table('mentors')
    // Join with mappings to ensure mentor is mapped
    ->join('mappings', 'mentors.id', '=', 'mappings.mentorname_id')

    // Join with sessions
    ->leftJoin('sessions', 'mentors.id', '=', 'sessions.mentorname_id')

    // Join modules (optional – used here if needed for additional info)
    ->leftJoin('modules', 'sessions.modulename_id', '=', 'modules.id')

    ->select(
        'mentors.name as mentor_name',
        DB::raw('SUM(CASE WHEN sessions.done = 1 THEN 1 ELSE 0 END) as completed_sessions_count'),
        DB::raw('SUM(CASE WHEN sessions.done = 0 THEN 1 ELSE 0 END) as pending_sessions_count')
    )

    ->groupBy('mentors.id', 'mentors.name')
    ->get();


    // Formatting data for export with serial numbers
    return $data->map(function ($row, $index) {
        return [
            'Serial Number' => $index + 1,
            'Mentor' => $row->mentor_name,
            'Completed Sessions' => $row->completed_sessions_count ?? 0,
            'Pending Sessions' => $row->pending_sessions_count ?? 0,
        ];
    });
}



    public function headings(): array
    {
        return [
            'Serial Number',
            'Mentor',
            'Completed Sessions',
            'Pending Sessions',
        ];
    }
}
