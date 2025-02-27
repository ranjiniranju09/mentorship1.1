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
        $data = DB::table('modules')
            ->join('module_completion_tracker', 'modules.id', '=', 'module_completion_tracker.module_id')
            ->join('mentees', 'module_completion_tracker.mentee_id', '=', 'mentees.id')
            ->join('mentors', 'module_completion_tracker.user_id', '=', 'mentors.user_id')
            // Join the sessions table to get total session duration for each mentor
            ->leftJoin('sessions', 'mentors.id', '=', 'sessions.mentorname_id')
            ->select(
                'modules.id as module_id',
                'modules.name as module_name',
                'mentees.name as mentee_name',
                'mentors.name as mentor_name',
                DB::raw('COUNT(DISTINCT module_completion_tracker.module_id) as total_completed_modules'),
                DB::raw('COALESCE((SELECT GROUP_CONCAT(modules.name) 
                    FROM modules 
                    JOIN module_completion_tracker 
                    ON modules.id = module_completion_tracker.module_id 
                    WHERE module_completion_tracker.mentee_id = mentees.id 
                    GROUP BY mentees.id), "None") as completed_modules_names'),
                DB::raw('(SELECT COUNT(*) 
                    FROM modules 
                    WHERE modules.id NOT IN 
                    (SELECT module_id FROM module_completion_tracker 
                    WHERE mentee_id = module_completion_tracker.mentee_id)) as total_pending_modules'),
                DB::raw('(SELECT GROUP_CONCAT(modules.name) 
                    FROM modules 
                    WHERE modules.id NOT IN 
                    (SELECT module_id FROM module_completion_tracker 
                    WHERE mentee_id = module_completion_tracker.mentee_id)) as pending_modules_names'),
                // Calculate total session time in hours for each mentor
                DB::raw('SUM(sessions.session_duration_minutes) / 60 as total_hours_engaged')
            )
            ->groupBy('modules.id', 'mentees.id', 'mentors.id')
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
                'total_hours_engaged' => $item->total_hours_engaged ?? 0  // Add total hours engaged
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
