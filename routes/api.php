<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Modules
    Route::apiResource('modules', 'ModulesApiController');

    // Mentors
    Route::apiResource('mentors', 'MentorsApiController');

    // Mentees
    Route::apiResource('mentees', 'MenteesApiController');

    // Sessions
    Route::apiResource('sessions', 'SessionsApiController');

    // Languagespoken
    Route::apiResource('languagespokens', 'LanguagespokenApiController');

    // Skills
    Route::apiResource('skills', 'SkillsApiController');

    // Mapping
    Route::apiResource('mappings', 'MappingApiController');

    // Survey Form
    Route::apiResource('survey-forms', 'SurveyFormApiController');

    // Guestspeakers
    Route::apiResource('guestspeakers', 'GuestspeakersApiController');

    // Session Recording
    Route::post('session-recordings/media', 'SessionRecordingApiController@storeMedia')->name('session-recordings.storeMedia');
    Route::apiResource('session-recordings', 'SessionRecordingApiController');
    

    // Guest Lectures
    Route::apiResource('guest-lectures', 'GuestLecturesApiController');

    // One One Attendance
    Route::apiResource('one-one-attendances', 'OneOneAttendanceApiController');

    // Guest Session Attendance
    Route::apiResource('guest-session-attendances', 'GuestSessionAttendanceApiController');

    // Courses
    Route::post('courses/media', 'CoursesApiController@storeMedia')->name('courses.storeMedia');
    Route::apiResource('courses', 'CoursesApiController');

    // Lessons
    Route::post('lessons/media', 'LessonsApiController@storeMedia')->name('lessons.storeMedia');
    Route::apiResource('lessons', 'LessonsApiController');

    // Tests
    Route::apiResource('tests', 'TestsApiController');

    // Questions
    Route::post('questions/media', 'QuestionsApiController@storeMedia')->name('questions.storeMedia');
    Route::apiResource('questions', 'QuestionsApiController');

    // Question Options
    Route::apiResource('question-options', 'QuestionOptionsApiController');

    // Test Results
    Route::apiResource('test-results', 'TestResultsApiController');

    // Test Answers
    Route::apiResource('test-answers', 'TestAnswersApiController');

    // Assign Tasks
    Route::post('assign-tasks/media', 'AssignTasksApiController@storeMedia')->name('assign-tasks.storeMedia');
    Route::apiResource('assign-tasks', 'AssignTasksApiController');

    // Chapters
    Route::apiResource('chapters', 'ChaptersApiController');

    // Chapter Test
    Route::apiResource('chapter-tests', 'ChapterTestApiController');

    // Subchapter
    Route::post('subchapters/media', 'SubchapterApiController@storeMedia')->name('subchapters.storeMedia');
    Route::apiResource('subchapters', 'SubchapterApiController');

    // Create Progress Table
    Route::apiResource('create-progress-tables', 'CreateProgressTableApiController');

        // Opportunities
    Route::apiResource('opportunities', 'OpportunitiesApiController');

    // Moduleresourcebank
    Route::post('moduleresourcebanks/media', 'ModuleresourcebankApiController@storeMedia')->name('moduleresourcebanks.storeMedia');
    Route::apiResource('moduleresourcebanks', 'ModuleresourcebankApiController');

    // Ticketcategory
    Route::apiResource('ticketcategories', 'TicketcategoryApiController');

    // Ticket Description
    Route::post('ticket-descriptions/media', 'TicketDescriptionApiController@storeMedia')->name('ticket-descriptions.storeMedia');
    Route::apiResource('ticket-descriptions', 'TicketDescriptionApiController');

    // Ticket Response
    Route::post('ticket-responses/media', 'TicketResponseApiController@storeMedia')->name('ticket-responses.storeMedia');
    Route::apiResource('ticket-responses', 'TicketResponseApiController');

    
});

