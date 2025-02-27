<?php

namespace Database\Seeders;

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'module_create',
            ],
            [
                'id'    => 20,
                'title' => 'module_edit',
            ],
            [
                'id'    => 21,
                'title' => 'module_show',
            ],
            [
                'id'    => 22,
                'title' => 'module_delete',
            ],
            [
                'id'    => 23,
                'title' => 'module_access',
            ],
            [
                'id'    => 24,
                'title' => 'mentor_create',
            ],
            [
                'id'    => 25,
                'title' => 'mentor_edit',
            ],
            [
                'id'    => 26,
                'title' => 'mentor_show',
            ],
            [
                'id'    => 27,
                'title' => 'mentor_delete',
            ],
            [
                'id'    => 28,
                'title' => 'mentor_access',
            ],
            [
                'id'    => 29,
                'title' => 'mentee_create',
            ],
            [
                'id'    => 30,
                'title' => 'mentee_edit',
            ],
            [
                'id'    => 31,
                'title' => 'mentee_show',
            ],
            [
                'id'    => 32,
                'title' => 'mentee_delete',
            ],
            [
                'id'    => 33,
                'title' => 'mentee_access',
            ],
            [
                'id'    => 34,
                'title' => 'session_create',
            ],
            [
                'id'    => 35,
                'title' => 'session_edit',
            ],
            [
                'id'    => 36,
                'title' => 'session_show',
            ],
            [
                'id'    => 37,
                'title' => 'session_delete',
            ],
            [
                'id'    => 38,
                'title' => 'session_access',
            ],
            [
                'id'    => 39,
                'title' => 'master_data_access',
            ],
            [
                'id'    => 40,
                'title' => 'languagespoken_create',
            ],
            [
                'id'    => 41,
                'title' => 'languagespoken_edit',
            ],
            [
                'id'    => 42,
                'title' => 'languagespoken_show',
            ],
            [
                'id'    => 43,
                'title' => 'languagespoken_delete',
            ],
            [
                'id'    => 44,
                'title' => 'languagespoken_access',
            ],
            [
                'id'    => 45,
                'title' => 'skill_create',
            ],
            [
                'id'    => 46,
                'title' => 'skill_edit',
            ],
            [
                'id'    => 47,
                'title' => 'skill_show',
            ],
            [
                'id'    => 48,
                'title' => 'skill_delete',
            ],
            [
                'id'    => 49,
                'title' => 'skill_access',
            ],
            [
                'id'    => 50,
                'title' => 'mapping_create',
            ],
            [
                'id'    => 51,
                'title' => 'mapping_edit',
            ],
            [
                'id'    => 52,
                'title' => 'mapping_show',
            ],
            [
                'id'    => 53,
                'title' => 'mapping_delete',
            ],
            [
                'id'    => 54,
                'title' => 'mapping_access',
            ],
            [
                'id'    => 55,
                'title' => 'survey_form_create',
            ],
            [
                'id'    => 56,
                'title' => 'survey_form_edit',
            ],
            [
                'id'    => 57,
                'title' => 'survey_form_show',
            ],
            [
                'id'    => 58,
                'title' => 'survey_form_delete',
            ],
            [
                'id'    => 59,
                'title' => 'survey_form_access',
            ],
            [
                'id'    => 60,
                'title' => 'guestspeaker_create',
            ],
            [
                'id'    => 61,
                'title' => 'guestspeaker_edit',
            ],
            [
                'id'    => 62,
                'title' => 'guestspeaker_show',
            ],
            [
                'id'    => 63,
                'title' => 'guestspeaker_delete',
            ],
            [
                'id'    => 64,
                'title' => 'guestspeaker_access',
            ],
            [
                'id'    => 65,
                'title' => 'session_recording_create',
            ],
            [
                'id'    => 66,
                'title' => 'session_recording_edit',
            ],
            [
                'id'    => 67,
                'title' => 'session_recording_show',
            ],
            [
                'id'    => 68,
                'title' => 'session_recording_delete',
            ],
            [
                'id'    => 69,
                'title' => 'session_recording_access',
            ],
            [
                'id'    => 70,
                'title' => 'guest_lecture_create',
            ],
            [
                'id'    => 71,
                'title' => 'guest_lecture_edit',
            ],
            [
                'id'    => 72,
                'title' => 'guest_lecture_show',
            ],
            [
                'id'    => 73,
                'title' => 'guest_lecture_delete',
            ],
            [
                'id'    => 74,
                'title' => 'guest_lecture_access',
            ],
            [
                'id'    => 75,
                'title' => 'guest_session_access',
            ],
            [
                'id'    => 76,
                'title' => 'one_one_session_access',
            ],
            [
                'id'    => 77,
                'title' => 'one_one_attendance_create',
            ],
            [
                'id'    => 78,
                'title' => 'one_one_attendance_edit',
            ],
            [
                'id'    => 79,
                'title' => 'one_one_attendance_show',
            ],
            [
                'id'    => 80,
                'title' => 'one_one_attendance_delete',
            ],
            [
                'id'    => 81,
                'title' => 'one_one_attendance_access',
            ],
            [
                'id'    => 82,
                'title' => 'guest_session_attendance_create',
            ],
            [
                'id'    => 83,
                'title' => 'guest_session_attendance_edit',
            ],
            [
                'id'    => 84,
                'title' => 'guest_session_attendance_show',
            ],
            [
                'id'    => 85,
                'title' => 'guest_session_attendance_delete',
            ],
            [
                'id'    => 86,
                'title' => 'guest_session_attendance_access',
            ],
            [
                'id'    => 87,
                'title' => 'course_create',
            ],
            [
                'id'    => 88,
                'title' => 'course_edit',
            ],
            [
                'id'    => 89,
                'title' => 'course_show',
            ],
            [
                'id'    => 90,
                'title' => 'course_delete',
            ],
            [
                'id'    => 91,
                'title' => 'course_access',
            ],
            [
                'id'    => 92,
                'title' => 'lesson_create',
            ],
            [
                'id'    => 93,
                'title' => 'lesson_edit',
            ],
            [
                'id'    => 94,
                'title' => 'lesson_show',
            ],
            [
                'id'    => 95,
                'title' => 'lesson_delete',
            ],
            [
                'id'    => 96,
                'title' => 'lesson_access',
            ],
            [
                'id'    => 97,
                'title' => 'test_create',
            ],
            [
                'id'    => 98,
                'title' => 'test_edit',
            ],
            [
                'id'    => 99,
                'title' => 'test_show',
            ],
            [
                'id'    => 100,
                'title' => 'test_delete',
            ],
            [
                'id'    => 101,
                'title' => 'test_access',
            ],
            [
                'id'    => 102,
                'title' => 'question_create',
            ],
            [
                'id'    => 103,
                'title' => 'question_edit',
            ],
            [
                'id'    => 104,
                'title' => 'question_show',
            ],
            [
                'id'    => 105,
                'title' => 'question_delete',
            ],
            [
                'id'    => 106,
                'title' => 'question_access',
            ],
            [
                'id'    => 107,
                'title' => 'question_option_create',
            ],
            [
                'id'    => 108,
                'title' => 'question_option_edit',
            ],
            [
                'id'    => 109,
                'title' => 'question_option_show',
            ],
            [
                'id'    => 110,
                'title' => 'question_option_delete',
            ],
            [
                'id'    => 111,
                'title' => 'question_option_access',
            ],
            [
                'id'    => 112,
                'title' => 'test_result_create',
            ],
            [
                'id'    => 113,
                'title' => 'test_result_edit',
            ],
            [
                'id'    => 114,
                'title' => 'test_result_show',
            ],
            [
                'id'    => 115,
                'title' => 'test_result_delete',
            ],
            [
                'id'    => 116,
                'title' => 'test_result_access',
            ],
            [
                'id'    => 117,
                'title' => 'test_answer_create',
            ],
            [
                'id'    => 118,
                'title' => 'test_answer_edit',
            ],
            [
                'id'    => 119,
                'title' => 'test_answer_show',
            ],
            [
                'id'    => 120,
                'title' => 'test_answer_delete',
            ],
            [
                'id'    => 121,
                'title' => 'test_answer_access',
            ],
            [
                'id'    => 122,
                'title' => 'modules_master_access',
            ],
            [
                'id'    => 123,
                'title' => 'assign_task_create',
            ],
            [
                'id'    => 124,
                'title' => 'assign_task_edit',
            ],
            [
                'id'    => 125,
                'title' => 'assign_task_show',
            ],
            [
                'id'    => 126,
                'title' => 'assign_task_delete',
            ],
            [
                'id'    => 127,
                'title' => 'assign_task_access',
            ],
            
            [
                'id'    => 128,
                'title' => 'chapter_create',
            ],
            [
                'id'    => 129,
                'title' => 'chapter_edit',
            ],
            [
                'id'    => 130,
                'title' => 'chapter_show',
            ],
            [
                'id'    => 131,
                'title' => 'chapter_delete',
            ],
            [
                'id'    => 132,
                'title' => 'chapter_access',
            ],
            [
                'id'    => 133,
                'title' => 'chapter_test_create',
            ],
            [
                'id'    => 134,
                'title' => 'chapter_test_edit',
            ],
            [
                'id'    => 135,
                'title' => 'chapter_test_show',
            ],
            [
                'id'    => 136,
                'title' => 'chapter_test_delete',
            ],
            [
                'id'    => 137,
                'title' => 'chapter_test_access',
            ],
            [
                'id'    => 138,
                'title' => 'subchapter_create',
            ],
            [
                'id'    => 139,
                'title' => 'subchapter_edit',
            ],
            [
                'id'    => 140,
                'title' => 'subchapter_show',
            ],
            [
                'id'    => 141,
                'title' => 'subchapter_delete',
            ],
            [
                'id'    => 142,
                'title' => 'subchapter_access',
            ],
            [
                'id'    => 143,
                'title' => 'create_progress_table_create',
            ],
            [
                'id'    => 144,
                'title' => 'create_progress_table_edit',
            ],
            [
                'id'    => 145,
                'title' => 'create_progress_table_show',
            ],
            [
                'id'    => 146,
                'title' => 'create_progress_table_delete',
            ],
            [
                'id'    => 147,
                'title' => 'create_progress_table_access',
            ],
            [
                'id'    => 148,
                'title' => 'opportunity_create',
            ],
            [
                'id'    => 149,
                'title' => 'opportunity_edit',
            ],
            [
                'id'    => 150,
                'title' => 'opportunity_show',
            ],
            [
                'id'    => 151,
                'title' => 'opportunity_delete',
            ],
            [
                'id'    => 152,
                'title' => 'opportunity_access',
            ],
            [
                'id'    => 153,
                'title' => 'moduleresourcebank_create',
            ],
            [
                'id'    => 154,
                'title' => 'moduleresourcebank_edit',
            ],
            [
                'id'    => 155,
                'title' => 'moduleresourcebank_show',
            ],
            [
                'id'    => 156,
                'title' => 'moduleresourcebank_delete',
            ],
            [
                'id'    => 157,
                'title' => 'moduleresourcebank_access',
            ],
            [
                'id'    => 158,
                'title' => 'support_access',
            ],
            [
                'id'    => 159,
                'title' => 'ticketcategory_create',
            ],
            [
                'id'    => 160,
                'title' => 'ticketcategory_edit',
            ],
            [
                'id'    => 161,
                'title' => 'ticketcategory_show',
            ],
            [
                'id'    => 162,
                'title' => 'ticketcategory_delete',
            ],
            [
                'id'    => 163,
                'title' => 'ticketcategory_access',
            ],
            [
                'id'    => 164,
                'title' => 'ticket_description_create',
            ],
            [
                'id'    => 165,
                'title' => 'ticket_description_edit',
            ],
            [
                'id'    => 166,
                'title' => 'ticket_description_show',
            ],
            [
                'id'    => 167,
                'title' => 'ticket_description_delete',
            ],
            [
                'id'    => 168,
                'title' => 'ticket_description_access',
            ],
            [
                'id'    => 169,
                'title' => 'ticket_response_create',
            ],
            [
                'id'    => 170,
                'title' => 'ticket_response_edit',
            ],
            [
                'id'    => 171,
                'title' => 'ticket_response_show',
            ],
            [
                'id'    => 172,
                'title' => 'ticket_response_delete',
            ],
            [
                'id'    => 173,
                'title' => 'ticket_response_access',
            ],

            [
                'id'    => 174,
                'title' => 'profile_password_edit',
            ],

        ];

        Permission::insert($permissions);
    }
}
