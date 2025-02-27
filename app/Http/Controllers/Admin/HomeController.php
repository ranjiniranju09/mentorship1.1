<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\DB;

class HomeController
{
    public function index()
    {
        $settings1 = [
            'chart_title'           => 'Total Mentor Mentee Pairs',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Mapping',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_period'         => 'year',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'mapping',
        ];

        $settings1['total_number'] = 0;

        // Fetch total mentors count
        $totalMappedpairs = DB::table('mappings')->count();

        // if (class_exists($settings1['model'])) {
        //     $settings1['total_number'] = $settings1['model']::when(isset($settings1['filter_field']), function ($query) use ($settings1) {
        //         if (isset($settings1['filter_days'])) {
        //             return $query->where($settings1['filter_field'], '>=',
        //                 now()->subDays($settings1['filter_days'])->format('Y-m-d'));
        //         } elseif (isset($settings1['filter_period'])) {
        //             switch ($settings1['filter_period']) {
        //                 case 'week': $start = date('Y-m-d', strtotime('last Monday'));
        //                 break;
        //                 case 'month': $start = date('Y-m') . '-01';
        //                 break;
        //                 case 'year': $start = date('Y') . '-01-01';
        //                 break;
        //             }
        //             if (isset($start)) {
        //                 return $query->where($settings1['filter_field'], '>=', $start);
        //             }
        //         }
        //     })
        //         ->{$settings1['aggregate_function'] ?? 'count'}($settings1['aggregate_field'] ?? '*');
        // }

        $settings2 = [
            'chart_title'           => 'Registered Mentors',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Mentor',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_period'         => 'year',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'mentor',
        ];

        $settings2['total_number'] = 0;

        // Fetch total mentors count
        $totalMentors = DB::table('mentors')->count();

        
        // if (class_exists($settings2['model'])) {
        //     $settings2['total_number'] = $settings2['model']::when(isset($settings2['filter_field']), function ($query) use ($settings2) {
        //         if (isset($settings2['filter_days'])) {
        //             return $query->where($settings2['filter_field'], '>=',
        //                 now()->subDays($settings2['filter_days'])->format('Y-m-d'));
        //         } elseif (isset($settings2['filter_period'])) {
        //             switch ($settings2['filter_period']) {
        //                 case 'week': $start = date('Y-m-d', strtotime('last Monday'));
        //                 break;
        //                 case 'month': $start = date('Y-m') . '-01';
        //                 break;
        //                 case 'year': $start = date('Y') . '-01-01';
        //                 break;
        //             }
        //             if (isset($start)) {
        //                 return $query->where($settings2['filter_field'], '>=', $start);
        //             }
        //         }
        //     })
        //         ->{$settings2['aggregate_function'] ?? 'count'}($settings2['aggregate_field'] ?? '*');
        // }

        $settings3 = [
            'chart_title'           => 'Registered Mentees',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Mentee',
            'group_by_field'        => 'dob',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_period'         => 'year',
            'group_by_field_format' => 'd-m-Y',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'mentee',
        ];

        $settings3['total_number'] = 0;

        // Fetch total mentors count
        $totalMentees = DB::table('mentees')->count();


        // if (class_exists($settings3['model'])) {
        //     $settings3['total_number'] = $settings3['model']::when(isset($settings3['filter_field']), function ($query) use ($settings3) {
        //         if (isset($settings3['filter_days'])) {
        //             return $query->where($settings3['filter_field'], '>=',
        //                 now()->subDays($settings3['filter_days'])->format('Y-m-d'));
        //         } elseif (isset($settings3['filter_period'])) {
        //             switch ($settings3['filter_period']) {
        //                 case 'week': $start = date('Y-m-d', strtotime('last Monday'));
        //                 break;
        //                 case 'month': $start = date('Y-m') . '-01';
        //                 break;
        //                 case 'year': $start = date('Y') . '-01-01';
        //                 break;
        //             }
        //             if (isset($start)) {
        //                 return $query->where($settings3['filter_field'], '>=', $start);
        //             }
        //         }
        //     })
        //         ->{$settings3['aggregate_function'] ?? 'count'}($settings3['aggregate_field'] ?? '*');
        // }

        $settings4 = [
            'chart_title'           => 'Total Sessions',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Session',
            'group_by_field'        => 'sessiondatetime',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_period'         => 'year',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'session',
        ];

        $settings4['total_number'] = 0;
        if (class_exists($settings4['model'])) {
            $settings4['total_number'] = $settings4['model']::when(isset($settings4['filter_field']), function ($query) use ($settings4) {
                if (isset($settings4['filter_days'])) {
                    return $query->where($settings4['filter_field'], '>=',
                        now()->subDays($settings4['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings4['filter_period'])) {
                    switch ($settings4['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings4['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings4['aggregate_function'] ?? 'count'}($settings4['aggregate_field'] ?? '*');
        }

        $settings5 = [
            'chart_title'           => 'Total Guest Lectures',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\GuestLecture',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_period'         => 'year',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'guestLecture',
        ];

        $settings5['total_number'] = 0;
        if (class_exists($settings5['model'])) {
            $settings5['total_number'] = $settings5['model']::when(isset($settings5['filter_field']), function ($query) use ($settings5) {
                if (isset($settings5['filter_days'])) {
                    return $query->where($settings5['filter_field'], '>=',
                        now()->subDays($settings5['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings5['filter_period'])) {
                    switch ($settings5['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings5['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings5['aggregate_function'] ?? 'count'}($settings5['aggregate_field'] ?? '*');
        }

        $settings6 = [
            'chart_title'           => 'Total Hours Engaged',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Session',
            'group_by_field'        => 'sessiondatetime',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_period'         => 'month',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'session',
        ];

        $settings6['total_number'] = 0;
        if (class_exists($settings6['model'])) {
            $settings6['total_number'] = $settings6['model']::when(isset($settings6['filter_field']), function ($query) use ($settings6) {
                if (isset($settings6['filter_days'])) {
                    return $query->where($settings6['filter_field'], '>=',
                        now()->subDays($settings6['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings6['filter_period'])) {
                    switch ($settings6['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings6['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings6['aggregate_function'] ?? 'count'}($settings6['aggregate_field'] ?? '*');
        }

        $settings7 = [
            'chart_title'           => 'Recent Sessions',
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Session',
            'group_by_field'        => 'sessiondatetime',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '14',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '10',
            'fields'                => [
                'session_title' => '',
            ],
            'translation_key' => 'session',
        ];

        $settings7['data'] = [];
        if (class_exists($settings7['model'])) {
            $settings7['data'] = $settings7['model']::latest()
                ->take($settings7['entries_number'])
                ->get();
        }

        if (! array_key_exists('fields', $settings7)) {
            $settings7['fields'] = [];
        }

        $settings8 = [
            'chart_title'           => 'Sessions',
            'chart_type'            => 'bar',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Session',
            'group_by_field'        => 'sessiondatetime',
            'group_by_period'       => 'week',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '30',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-6',
            'entries_number'        => '5',
            'translation_key'       => 'session',
        ];

        $chart8 = new LaravelChart($settings8);

        $unmappedMentorsCount = DB::table('mentors')
            ->leftJoin('mappings', 'mentors.id', '=', 'mappings.mentorname_id')
            ->whereNull('mappings.mentorname_id')
            ->count();

        // Count unmapped mentees
        $unmappedMenteesCount = DB::table('mentees')
            ->leftJoin('mappings', 'mentees.id', '=', 'mappings.menteename_id')
            ->whereNull('mappings.menteename_id')
            ->count();

        return view('home', compact('chart8', 'settings1','totalMappedpairs', 'settings2', 'totalMentors', 'totalMentees', 'settings3', 'settings4', 'settings5', 'settings6', 'settings7','unmappedMentorsCount', 'unmappedMenteesCount'));
    }
}
