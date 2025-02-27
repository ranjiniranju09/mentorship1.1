<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Facades\DB;

class ModuleReportExport implements FromCollection, WithStyles, WithHeadings
{
    /**
     * Return the collection of data to be exported.
     */
    public function collection()
    {
        // Fetch progress data
        $data = DB::table('module_completion_tracker')
    ->leftJoin('mappings', 'module_completion_tracker.mentee_id', '=', 'mappings.menteename_id')
    ->leftJoin('mentees', 'mappings.menteename_id', '=', 'mentees.id')
    ->leftJoin('mentors', 'mappings.mentorname_id', '=', 'mentors.id')
    ->leftJoin('modules', 'module_completion_tracker.module_id', '=', 'modules.id')
    ->select(
        DB::raw('ROW_NUMBER() OVER (ORDER BY module_completion_tracker.completed_at DESC) as serial_number'),
        'mentees.name as mentee_name',
        'mentors.name as mentor_name',
        'modules.name as module_name',
        'module_completion_tracker.completed_at'
    )
    ->get();


        return collect($data);
    }

    /**
     * Define column headings.
     */
    public function headings(): array
    {
        return [
            'Serial No.', 
            'Mentee Name', 
            'Mentor Name', 
            'Module Name', 
            'Completion Date'
        ];
    }

    /**
     * Apply styles to the spreadsheet.
     */
    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        // Apply styles to the header row
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Auto size all columns
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}
