<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\DB;

class ModuleProgressExport implements FromCollection, WithStyles
{

    public function collection()
    {
        
        // Fetch progress data and session duration for each mentor
         $data = DB::table('mentees')
            // Only include mapped mentees
            ->join('mappings', 'mentees.id', '=', 'mappings.menteename_id')

            // Join mentors using mapping
            ->join('mentors', 'mappings.mentorname_id', '=', 'mentors.id')

            // Join module completion tracker
            ->leftJoin('module_completion_tracker', 'mentees.id', '=', 'module_completion_tracker.mentee_id')

            // Join sessions for the mapped mentor
            ->leftJoin('sessions', function($join) {
                $join->on('mentors.id', '=', 'sessions.mentorname_id')
                    ->where('sessions.done', '=', 1);
            })

            ->select(
                'mentees.id as mentee_id',
                'mentees.name as mentee_name',
                'mentors.name as mentor_name',

                // Count of completed modules
                DB::raw('COUNT(DISTINCT module_completion_tracker.module_id) as total_completed_modules'),

                // Pending modules = total - completed
                DB::raw('(SELECT COUNT(*) FROM modules) - COUNT(DISTINCT module_completion_tracker.module_id) as total_pending_modules'),

                // Completed module names
                DB::raw('(
                    SELECT GROUP_CONCAT(DISTINCT m.name)
                    FROM modules m
                    JOIN module_completion_tracker mct ON m.id = mct.module_id
                    WHERE mct.mentee_id = mentees.id
                ) as completed_modules_names'),

                // Pending module names
                DB::raw('(
                    SELECT GROUP_CONCAT(name)
                    FROM modules
                    WHERE id NOT IN (
                        SELECT module_id 
                        FROM module_completion_tracker 
                        WHERE mentee_id = mentees.id
                    )
                ) as pending_modules_names'),

                // Total mentor session time in hours
                DB::raw('SUM(sessions.session_duration_minutes) / 60 as total_hours_engaged')
            )

            ->groupBy('mentees.id', 'mentees.name', 'mentors.name')
            ->get();

        // Map data for export
        $dataWithSerial = $data->map(function ($item, $index) {
            return [
                'serial_number' => $index + 1,
                'mentee_name' => $item->mentee_name ?? 'N/A',
                'mentor_name' => $item->mentor_name ?? 'N/A',
                'total_completed_modules' => $item->total_completed_modules ?? 0,
                'completed_modules_names' => $item->completed_modules_names ?? 'None',
                'total_pending_modules' => $item->total_pending_modules ?? 0,
                'pending_modules_names' => $item->pending_modules_names ?? 'None',
                'total_hours_engaged' => $item->total_hours_engaged ?? 0
            ];
        });

    
        // Header
        $header = [
            'Serial No.', 'Mentee Name', 'Mentor Name', 'Total Modules Completed', 'Completed Module Names', 
            'Total Pending Modules', 'Pending Module Names', 'Total Hours Engaged' // Add column for total hours engaged
        ];
    
        // Combine header and data
        return collect([$header])->merge($dataWithSerial);
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $lastColumn = Coordinate::stringFromColumnIndex(8); // Adjust dynamically based on headers (total hours engaged column)
        $headerRange = "A1:{$lastColumn}1";
    
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        return [];
    }
}
