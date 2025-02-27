<?php
use App\Events\MessageSent;
use App\GuestLecture;
use App\Http\Controllers\Admin\DiscussionController;
use App\Http\Controllers\Admin\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\Auth\ResendVerificationController;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Admin\QuizprogressController;
use App\Http\Controllers\Admin\ProgressDataController;
use App\Http\Controllers\Mentor\SessionController;
use App\Http\Controllers\Mentor\TaskController;
use App\Http\Controllers\Mentor\CertificateController;
use App\Http\Controllers\Admin\SessionsController;
use App\Http\Controllers\Admin\SessionRecordingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Mentee\TicketsController;
use App\Http\Controllers\Admin\GuestLecturesController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\Mentor\TaskController as MentorTaskController;
use App\Http\Controllers\Mentee\TaskController as MenteeTaskController;
use App\Http\Controllers\ResourceApprovalController;
use App\Http\Controllers\Scholarship\ScholershipController;
use App\Http\Controllers\Admin\ModulesController;
use App\Http\Controllers\Admin\TicketResponseController;
use App\Http\Controllers\Mentee\MenteeModuleController;
use App\Http\Controllers\Mentee\OpportunitiesController;
use App\Http\Controllers\Mentee\KnowledgebankController;
use App\Http\Controllers\Mentor\ModuleController;
use App\Http\Controllers\Mentor\OpportunitiesController as MentorOpportunitiesController;
use App\Http\Controllers\Mentor\QuizController;
use App\Http\Controllers\Mentor\OpportunityController;
use App\Http\Controllers\Mentor\SupportController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Module;
use App\TicketResponse;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Auth;



// Route::get('admin/get-sessions', 'App\Http\Controllers\Admin\SessionRecordingsController@getSessions')->name('admin.get-sessions');
// Route::get('admin/session-recordings/create', 'App\Http\Controllers\Admin\SessionRecordingsController@create')->name('admin.sessionRecordings.create');
// Route::post('admin/session-recordings/store', 'App\Http\Controllers\Admin\SessionRecordingsController@store')->name('admin.sessionRecordings.store');

// Route::get('/get-sessions/{mentorId}', 'Admin\SessionRecordingController@getSessions')->name('get.sessions');

#Route::get('/getsessions1/{mentorId}', 'Admin\SessionRecordingController@getSessions1')->name('getsessions1');
// Route::get('/getsessions1/{mentorId}', 'Admin\SessionRecordingController@getSessions1')->name('getsessions1');

Route::match(['get', 'post'], '/resources/{mentorId}', 'App\Http\Controllers\ResourceController@show')->name('resources');
Route::match(['get', 'post'], '/storeresources/{mentorId}', 'App\Http\Controllers\ResourceController@store')->name('storeresources');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
Route::post('/resourcesstore', [ResourceController::class, 'store'])->name('resources.store');
Route::get('/resources/{id}', [ResourceController::class, 'show']);
Route::put('/resources/{id}', [ResourceController::class, 'update'])->name('resources.update');
Route::delete('/resources/{id}', [ResourceController::class, 'destroy'])->name('resources.destroy');

// Route::resource('resources', ResourceController::class);

Route::get('/approvals', [ResourceApprovalController::class, 'index']);
Route::get('/approvals/create', [ResourceApprovalController::class, 'create']);
Route::post('/approvals', [ResourceApprovalController::class, 'store']);
Route::get('/approvals/{id}', [ResourceApprovalController::class, 'show']);
Route::get('/approvals/{id}/edit', [ResourceApprovalController::class, 'edit']);
Route::put('/approvals/{id}', [ResourceApprovalController::class, 'update']);
Route::delete('/approvals/{id}', [ResourceApprovalController::class, 'destroy']);
//Route::get('resource-approvals', [ResourceApprovalController::class, 'index'])->name('resource_approvals.index');
Route::patch('resource-approvals/{approval}', [ResourceApprovalController::class, 'approve'])->name('resource_approvals.approve');

Route::get('/menteeshow',[RegisterController::class,'menteeshow'])->name('menteeshow');
Route::post('/registermentee',[RegisterController::class,'menteereg'])->name('menteereg');

Route::get('/mentorshow',[RegisterController::class,'mentorshow'])->name('mentorshow');

Route::get('/opportunity', [OpportunityController::class, 'index'])->name('opportunity.index');
Route::post('/opportunitystore', [OpportunityController::class, 'store'])->name('opportunity.store');
Route::delete('/opportunity/{id}', [OpportunityController::class, 'destroy'])->name('opportunity.destroy');
Route::put('/opportunity/{id}', [OpportunityController::class, 'update'])->name('opportunity.update');


// Route::post('/mentorreg', [RegisterController::class, 'mentorreg'])->name('mentorreg');
Route::post('/mentorreg','Auth\RegisterController@mentorreg')->name('mentorreg');


Route::view('/', 'welcome');
Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
//Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
//Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// routes/web.php


Route::get('/sendverificationForm', [ResendVerificationController::class, 'showVerificationForm'])->name('sendVerificationForm');
Route::post('/sendverificationMail', [ResendVerificationController::class, 'resendVerificationEmail'])->name('sendVerificationMail');

Route::get('/verify/{token}', [ResendVerificationController::class, 'verify'])->name('verification.verify');


Auth::routes();

Route::get('/mentee/dashboard', 'MenteeDashboardController@index')->name('mentee.dashboard');
Route::get('/mentor/dashboard', 'MentorDashboardController@index')->name('mentor.dashboard');


Route::prefix('mentor')->group(function() {


    Route::get('/manage-sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
    Route::put('/sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
    Route::get('/sessions/{session}/edit', [SessionController::class, 'edit'])->name('sessions.edit');    
    Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    Route::post('/upload-recording', [SessionController::class, 'uploadRecording'])->name('upload.recording');
    Route::get('/sessions/create-recording', [SessionController::class, 'createRecording'])->name('sessions.create-recording');
    Route::post('/sessions/recording', [SessionController::class, 'storeRecording'])->name('sessions.store-recording');

    // Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
    // Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
    // Route::get('/sessions/create-recording', [SessionController::class, 'createRecording'])->name('sessions.create-recording');
    // Route::post('/sessions/recording', [SessionController::class, 'storeRecording'])->name('sessions.store-recording');
    
    Route::get('/tasks-index', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks-index', [MentorTaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasksstore', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/menteemoduleprogress', [ModuleController::class, 'menteemoduleprogress'])->name('menteemoduleprogress');
    Route::post('/chapter-mark-completed/{mentee_id}', [ModuleController::class, 'markChapterCompleted'])->name('chapter.markCompleted');
    Route::post('/mentor/markChapterCompletion/{moduleId}', [ModuleController::class, 'markChapterCompletion'])->name('mentor.markChapterCompletion');
    Route::post('/chapter-mark-completed', [ModuleController::class, 'markChapterCompleted'])->name('chapter.markCompleted');

    Route::get('/moduleList',[ModuleController::class,'moduleList'])->name('moduleList');

    Route::get('/quizdetails',[QuizController::class,'quizdetails'])->name('quizdetails');
    Route::get('/quizprogress', [QuizController::class, 'getQuizResults'])->name('quizprogress');

    // Route::get('/tasksindex',[TaskController::class,'index'])->name('tasks.index');

    Route::get('/modules', [ModuleController::class, 'index'])->name('mentor.modules');

    Route::get('/showmentorchapters', [ModuleController::class, 'showmentorchapters'])->name('showmentorchapters');

    Route::get('/mentorsubchapter', [ModuleController::class, 'mentorsubchapter'])->name('mentor.mentorsubchapter');

    // Route::get('/mentorquiz', [ModuleController::class, 'mentorquiz'])->name('mentorquiz');

    Route::get('/quiz/{chapter_id}', [QuizController::class, 'showmentorquiz'])->name('mentor.quiz');

    Route::post('/discussionanswers/{id}/reply', [QuizController::class, 'storeMentorReply'])->name('discussionanswers.reply');


    Route::get('/mentor/verify/{token}', [RegisterController::class, 'verifymentor'])->name('mentor.verify');

    // Route::post('/modulecompletion', [ModuleController::class, 'markModuleAsCompleted'])->name('modulecompletion');

    Route::get('/calendar', [CalenderController::class, 'showmentorCalendar'])->name('mentorcalendar');

    Route::get('/certificate', [CertificateController::class, 'showmentorCertificate'])->name('mentorcertificate');

    Route::get('/download', [CertificateController::class, 'download'])->name('downloadcertificate');


    Route::get('/mentortickets', [SupportController::class, 'mentortickets'])->name('mentor.tickets');

    Route::get('/mentorticketscreate', [SupportController::class, 'mentorticketscreate'])->name('mentor.support.create');

    Route::POST('/mentorticketsstore', [SupportController::class, 'mentorticketstore'])->name('mentor.tickets.store');

    Route::delete('/mentor/tickets/{id}', [SupportController::class, 'destroy'])->name('mentor.tickets.destroy');




    
});


//Route::get('/mentee/dashboard', 'MenteeDashboardController@index')->name('mentee.dashboard');


Route::prefix('mentee')->group(function() {
    Route::get('/tasks-index', [MenteeTaskController::class, 'index'])->name('menteetasks.index');
    Route::post('/tasks/submit',[MenteeTaskController::class, 'submit'] )->name('tasks.submit');
    Route::post('/tasks/submitck', [MentorTaskController::class, 'storeCKEditorImages'])->name('tasks.storeCKEditorImages');

    Route::get('/tasks/show/{taskId}', [MenteeTaskController::class, 'show']);


    // Route::get('/menteesessions', [MenteeModuleController::class, 'index'])->name('menteesessions.index');

    Route::get('/knowledgebank', [KnowledgebankController::class, 'index'])->name('knowledgebank.index');
    
    Route::get('/menteemodule', [MenteeModuleController::class, 'index'])->name('menteemodule.index');

    Route::get('/calendar', [CalenderController::class, 'showCalendar'])->name('calendar');

    Route::get('/menteesessions', [MenteeModuleController::class, 'index'])->name('menteesessions.index');

    Route::get('/chapterscontent', [MenteeModuleController::class, 'showchapters'])->name('chapterscontent');

    Route::get('/subchaptercontent', [MenteeModuleController::class, 'subchaptercontent'])->name('subchaptercontent');

    Route::get('/viewquiz', [MenteeModuleController::class, 'viewquiz'])->name('viewquiz');

    Route::post('/quiz/submit', [MenteeModuleController::class, 'submitQuiz'])->name('quiz.submit');

    
    Route::get('/menteetickets', [MenteeModuleController::class, 'menteetickets'])->name('mentee.tickets');

    Route::get('/ticketscreate', [MenteeModuleController::class, 'ticketscreate'])->name('mentee.tickets.create');

    Route::POST('/menteeticketsstore', [MenteeModuleController::class, 'ticketstore'])->name('mentee.tickets.store');

    Route::delete('/mentee/tickets/{id}', [MenteeModuleController::class, 'destroy'])->name('mentee.tickets.destroy');


    Route::get('/opportunitiesindex', [OpportunitiesController::class, 'index'])->name('opportunities.index');

    Route::get('/discussionquestions/{chapter_id}', [MenteeModuleController::class, 'getDiscussionQuestions'])->name('getDiscussionQuestions');

    Route::POST('/discussionanswerstore/{chapter_id}', [MenteeModuleController::class, 'discussionanswerstore'])->name('discussionanswerstore');

    Route::get('/verify/mentee/{token}', [RegisterController::class, 'verifyMentee'])->name('mentee.verify');




});

//Route::get('/manage-sessions', 'Mentor/SessionController@index')->name('sessions.index');

Route::match(['post', 'put'], '/sessions/{id}/mark-as-done', 'MentorDashboardController@markAsDone')->name('sessions.mark-as-done');

Route::POST('modulestore','Admin\ModulesController@store')->name('modulestore');
Route::POST('moduleupdate/{id}', 'Admin\ModulesController@update')->name('moduleupdate');
Route::post('chapterstore','Admin\ChaptersController@store')->name('chapterstore');
Route::post('chapterupdate/{id}', 'Admin\ChaptersController@update')->name('chapterupdate');
Route::put('testupdate/{id}','Admin\TestsController@update')->name('testupdate');


// ProgressData
Route::get('/progressdata', [ProgressDataController::class, 'getModuleProgressData'])->name('admin.progressData.masterdata');
Route::get('/chapterProgress', [ProgressDataController::class, 'modulereport'])->name('admin.progressData.modulereport');
Route::get('/mastersession', [ProgressDataController::class, 'mastersessionprogress'])->name('admin.progressData.mastersessionreport');

Route::get('/moduleprogress/export', [ProgressDataController::class, 'exportModuleProgress'])->name('admin.moduleprogress.export');
Route::get('/Chapterwiseprogress/export', [ProgressDataController::class, 'exportmodulereport'])->name('admin.modulereport.export');
Route::get('/mastersession/export', [ProgressDataController::class, 'exportSessionProgress'])->name('admin.mastersessionprogress.export');
Route::get('/mentorwisemodulestatus', [ProgressDataController::class, 'mentorwiseModuleStatus'])->name('admin.mentorwise.module.mentorwisemodule');
Route::get('/mentorwisemodulestatus/export', [ProgressDataController::class, 'exportMentorwiseModuleStatus'])->name('admin.mentorwisemodule.export');

Route::get('/quizCompletionReport', [ProgressDataController::class, 'quizCompletionReport'])->name('admin.quizCompletionReport');
Route::get('/quizCompletionReport/export', [ProgressDataController::class, 'exportQuizCompletedreport'])->name('admin.QuizCompleted.export');

Route::get('/uploadRecording', [GuestLecturesController::class, 'uploadRecording'])->name('admin.guestLectures.uploadRecording');

Route::post('/storeRecording', [GuestLecturesController::class, 'storeRecording'])->name('admin.guestLectures.storeRecording');

Route::get('session-recordings/create', [SessionRecordingController::class, 'create'])->name('sessionRecordings.create');
Route::post('session-recordings', [SessionRecordingController::class, 'store'])->name('admin.sessionRecordings.store');


Route::get('/dashboard', [HomeController::class, 'index1'])->name('index1');

Route::get('/login1', [LoginController::class, 'login1'])->name('login1');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Modules
    Route::delete('modules/destroy', 'ModulesController@massDestroy')->name('modules.massDestroy');
    Route::post('modules/parse-csv-import', 'ModulesController@parseCsvImport')->name('modules.parseCsvImport');
    Route::post('modules/process-csv-import', 'ModulesController@processCsvImport')->name('modules.processCsvImport');
    Route::resource('modules', 'ModulesController');
    // Route::resource('modules', ModulesController::class);

    // Mentors
    Route::delete('mentors/destroy', 'MentorsController@massDestroy')->name('mentors.massDestroy');
    Route::post('mentors/parse-csv-import', 'MentorsController@parseCsvImport')->name('mentors.parseCsvImport');
    Route::post('mentors/process-csv-import', 'MentorsController@processCsvImport')->name('mentors.processCsvImport');
    Route::resource('mentors', 'MentorsController');

    // Mentees
    Route::delete('mentees/destroy', 'MenteesController@massDestroy')->name('mentees.massDestroy');
    Route::post('mentees/parse-csv-import', 'MenteesController@parseCsvImport')->name('mentees.parseCsvImport');
    Route::post('mentees/process-csv-import', 'MenteesController@processCsvImport')->name('mentees.processCsvImport');
    Route::resource('mentees', 'MenteesController');

    // Sessions
    Route::delete('sessions/destroy', 'SessionsController@massDestroy')->name('sessions.massDestroy');
    Route::post('sessions/parse-csv-import', 'SessionsController@parseCsvImport')->name('sessions.parseCsvImport');
    Route::post('sessions/process-csv-import', 'SessionsController@processCsvImport')->name('sessions.processCsvImport');
    Route::resource('sessions', 'SessionsController');

    // Languagespoken
    Route::delete('languagespokens/destroy', 'LanguagespokenController@massDestroy')->name('languagespokens.massDestroy');
    Route::post('languagespokens/parse-csv-import', 'LanguagespokenController@parseCsvImport')->name('languagespokens.parseCsvImport');
    Route::post('languagespokens/process-csv-import', 'LanguagespokenController@processCsvImport')->name('languagespokens.processCsvImport');
    Route::resource('languagespokens', 'LanguagespokenController');

    // Skills
    Route::delete('skills/destroy', 'SkillsController@massDestroy')->name('skills.massDestroy');
    Route::post('skills/parse-csv-import', 'SkillsController@parseCsvImport')->name('skills.parseCsvImport');
    Route::post('skills/process-csv-import', 'SkillsController@processCsvImport')->name('skills.processCsvImport');
    Route::resource('skills', 'SkillsController');

    // Mapping
    Route::delete('mappings/destroy', 'MappingController@massDestroy')->name('mappings.massDestroy');
    Route::post('mappings/parse-csv-import', 'MappingController@parseCsvImport')->name('mappings.parseCsvImport');
    Route::post('mappings/process-csv-import', 'MappingController@processCsvImport')->name('mappings.processCsvImport');
    Route::resource('mappings', 'MappingController');

    // Survey Form
    Route::delete('survey-forms/destroy', 'SurveyFormController@massDestroy')->name('survey-forms.massDestroy');
    Route::post('survey-forms/parse-csv-import', 'SurveyFormController@parseCsvImport')->name('survey-forms.parseCsvImport');
    Route::post('survey-forms/process-csv-import', 'SurveyFormController@processCsvImport')->name('survey-forms.processCsvImport');
    Route::resource('survey-forms', 'SurveyFormController');

    // Guestspeakers
    Route::delete('guestspeakers/destroy', 'GuestspeakersController@massDestroy')->name('guestspeakers.massDestroy');
    Route::post('guestspeakers/parse-csv-import', 'GuestspeakersController@parseCsvImport')->name('guestspeakers.parseCsvImport');
    Route::post('guestspeakers/process-csv-import', 'GuestspeakersController@processCsvImport')->name('guestspeakers.processCsvImport');
    Route::resource('guestspeakers', 'GuestspeakersController');

    // Session Recording
    Route::delete('session-recordings/destroy', 'SessionRecordingController@massDestroy')->name('session-recordings.massDestroy');
    Route::post('session-recordings/media', 'SessionRecordingController@storeMedia')->name('session-recordings.storeMedia');
    Route::post('session-recordings/ckmedia', 'SessionRecordingController@storeCKEditorImages')->name('session-recordings.storeCKEditorImages');
    Route::post('session-recordings/parse-csv-import', 'SessionRecordingController@parseCsvImport')->name('session-recordings.parseCsvImport');
    Route::post('session-recordings/process-csv-import', 'SessionRecordingController@processCsvImport')->name('session-recordings.processCsvImport');
    Route::resource('session-recordings', 'SessionRecordingController');

    // Guest Lectures
    Route::delete('guest-lectures/destroy', 'GuestLecturesController@massDestroy')->name('guest-lectures.massDestroy');
    Route::post('guest-lectures/parse-csv-import', 'GuestLecturesController@parseCsvImport')->name('guest-lectures.parseCsvImport');
    Route::post('guest-lectures/process-csv-import', 'GuestLecturesController@processCsvImport')->name('guest-lectures.processCsvImport');
    Route::resource('guest-lectures', 'GuestLecturesController');

    // One One Attendance
    Route::delete('one-one-attendances/destroy', 'OneOneAttendanceController@massDestroy')->name('one-one-attendances.massDestroy');
    Route::post('one-one-attendances/parse-csv-import', 'OneOneAttendanceController@parseCsvImport')->name('one-one-attendances.parseCsvImport');
    Route::post('one-one-attendances/process-csv-import', 'OneOneAttendanceController@processCsvImport')->name('one-one-attendances.processCsvImport');
    Route::resource('one-one-attendances', 'OneOneAttendanceController');

    // Guest Session Attendance
    Route::delete('guest-session-attendances/destroy', 'GuestSessionAttendanceController@massDestroy')->name('guest-session-attendances.massDestroy');
    Route::post('guest-session-attendances/parse-csv-import', 'GuestSessionAttendanceController@parseCsvImport')->name('guest-session-attendances.parseCsvImport');
    Route::post('guest-session-attendances/process-csv-import', 'GuestSessionAttendanceController@processCsvImport')->name('guest-session-attendances.processCsvImport');
    Route::resource('guest-session-attendances', 'GuestSessionAttendanceController');

    // Courses
    Route::delete('courses/destroy', 'CoursesController@massDestroy')->name('courses.massDestroy');
    Route::post('courses/media', 'CoursesController@storeMedia')->name('courses.storeMedia');
    Route::post('courses/ckmedia', 'CoursesController@storeCKEditorImages')->name('courses.storeCKEditorImages');
    Route::post('courses/parse-csv-import', 'CoursesController@parseCsvImport')->name('courses.parseCsvImport');
    Route::post('courses/process-csv-import', 'CoursesController@processCsvImport')->name('courses.processCsvImport');
    Route::resource('courses', 'CoursesController');

    // Lessons
    Route::delete('lessons/destroy', 'LessonsController@massDestroy')->name('lessons.massDestroy');
    Route::post('lessons/media', 'LessonsController@storeMedia')->name('lessons.storeMedia');
    Route::post('lessons/ckmedia', 'LessonsController@storeCKEditorImages')->name('lessons.storeCKEditorImages');
    Route::post('lessons/parse-csv-import', 'LessonsController@parseCsvImport')->name('lessons.parseCsvImport');
    Route::post('lessons/process-csv-import', 'LessonsController@processCsvImport')->name('lessons.processCsvImport');
    Route::resource('lessons', 'LessonsController');

    // Tests
    Route::delete('tests/destroy', 'TestsController@massDestroy')->name('tests.massDestroy');
    Route::post('tests/parse-csv-import', 'TestsController@parseCsvImport')->name('tests.parseCsvImport');
    Route::post('tests/process-csv-import', 'TestsController@processCsvImport')->name('tests.processCsvImport');
    Route::resource('tests', 'TestsController');

    // Questions
    Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    Route::post('questions/media', 'QuestionsController@storeMedia')->name('questions.storeMedia');
    Route::post('questions/ckmedia', 'QuestionsController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    Route::post('questions/parse-csv-import', 'QuestionsController@parseCsvImport')->name('questions.parseCsvImport');
    Route::post('questions/process-csv-import', 'QuestionsController@processCsvImport')->name('questions.processCsvImport');
    Route::resource('questions', 'QuestionsController');

    // Question Options
    Route::delete('question-options/destroy', 'QuestionOptionsController@massDestroy')->name('question-options.massDestroy');
    Route::post('question-options/parse-csv-import', 'QuestionOptionsController@parseCsvImport')->name('question-options.parseCsvImport');
    Route::post('question-options/process-csv-import', 'QuestionOptionsController@processCsvImport')->name('question-options.processCsvImport');
    Route::resource('question-options', 'QuestionOptionsController');

    //Discussion Answers
    // Route::delete('discussion-answers/destroy', 'DiscussionAnswersController@massDestroy')->name('discussion-answers.massDestroy');
    Route::get('/discussionindex', [DiscussionController::class, 'index'])->name('discussion.index');
    Route::get('/discussioncreate', [DiscussionController::class, 'create'])->name('discussion.create');


    // Test Results
    Route::delete('test-results/destroy', 'TestResultsController@massDestroy')->name('test-results.massDestroy');
    Route::post('test-results/parse-csv-import', 'TestResultsController@parseCsvImport')->name('test-results.parseCsvImport');
    Route::post('test-results/process-csv-import', 'TestResultsController@processCsvImport')->name('test-results.processCsvImport');
    Route::resource('test-results', 'TestResultsController');

    // Test Answers
    Route::delete('test-answers/destroy', 'TestAnswersController@massDestroy')->name('test-answers.massDestroy');
    Route::post('test-answers/parse-csv-import', 'TestAnswersController@parseCsvImport')->name('test-answers.parseCsvImport');
    Route::post('test-answers/process-csv-import', 'TestAnswersController@processCsvImport')->name('test-answers.processCsvImport');
    Route::resource('test-answers', 'TestAnswersController');

    // Assign Tasks
    Route::delete('assign-tasks/destroy', 'AssignTasksController@massDestroy')->name('assign-tasks.massDestroy');
    Route::post('assign-tasks/media', 'AssignTasksController@storeMedia')->name('assign-tasks.storeMedia');
    Route::post('assign-tasks/ckmedia', 'AssignTasksController@storeCKEditorImages')->name('assign-tasks.storeCKEditorImages');
    Route::post('assign-tasks/parse-csv-import', 'AssignTasksController@parseCsvImport')->name('assign-tasks.parseCsvImport');
    Route::post('assign-tasks/process-csv-import', 'AssignTasksController@processCsvImport')->name('assign-tasks.processCsvImport');
    Route::resource('assign-tasks', 'AssignTasksController');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');


     // Chapters
    Route::delete('chapters/destroy', 'ChaptersController@massDestroy')->name('chapters.massDestroy');
    Route::post('chapters/parse-csv-import', 'ChaptersController@parseCsvImport')->name('chapters.parseCsvImport');
    Route::post('chapters/process-csv-import', 'ChaptersController@processCsvImport')->name('chapters.processCsvImport');
    Route::resource('chapters', 'ChaptersController');

    // Chapter Test
    Route::delete('chapter-tests/destroy', 'ChapterTestController@massDestroy')->name('chapter-tests.massDestroy');
    Route::post('chapter-tests/parse-csv-import', 'ChapterTestController@parseCsvImport')->name('chapter-tests.parseCsvImport');
    Route::post('chapter-tests/process-csv-import', 'ChapterTestController@processCsvImport')->name('chapter-tests.processCsvImport');
    Route::resource('chapter-tests', 'ChapterTestController');

    // Subchapter
    Route::delete('subchapters/destroy', 'SubchapterController@massDestroy')->name('subchapters.massDestroy');
    Route::post('subchapters/media', 'SubchapterController@storeMedia')->name('subchapters.storeMedia');
    Route::post('subchapters/ckmedia', 'SubchapterController@storeCKEditorImages')->name('subchapters.storeCKEditorImages');
    Route::post('subchapters/parse-csv-import', 'SubchapterController@parseCsvImport')->name('subchapters.parseCsvImport');
    Route::post('subchapters/process-csv-import', 'SubchapterController@processCsvImport')->name('subchapters.processCsvImport');
    Route::resource('subchapters', 'SubchapterController');

    // Create Progress Table
    Route::delete('create-progress-tables/destroy', 'CreateProgressTableController@massDestroy')->name('create-progress-tables.massDestroy');
    Route::post('create-progress-tables/parse-csv-import', 'CreateProgressTableController@parseCsvImport')->name('create-progress-tables.parseCsvImport');
    Route::post('create-progress-tables/process-csv-import', 'CreateProgressTableController@processCsvImport')->name('create-progress-tables.processCsvImport');
    Route::resource('create-progress-tables', 'CreateProgressTableController');

 // Moduleresourcebank
    Route::delete('moduleresourcebanks/destroy', 'ModuleresourcebankController@massDestroy')->name('moduleresourcebanks.massDestroy');
    Route::post('moduleresourcebanks/media', 'ModuleresourcebankController@storeMedia')->name('moduleresourcebanks.storeMedia');
    Route::post('moduleresourcebanks/ckmedia', 'ModuleresourcebankController@storeCKEditorImages')->name('moduleresourcebanks.storeCKEditorImages');
    Route::post('moduleresourcebanks/parse-csv-import', 'ModuleresourcebankController@parseCsvImport')->name('moduleresourcebanks.parseCsvImport');
    Route::post('moduleresourcebanks/process-csv-import', 'ModuleresourcebankController@processCsvImport')->name('moduleresourcebanks.processCsvImport');
    Route::resource('moduleresourcebanks', 'ModuleresourcebankController');

// Opportunities
    Route::delete('opportunities/destroy', 'OpportunitiesController@massDestroy')->name('opportunities.massDestroy');
    Route::post('opportunities/parse-csv-import', 'OpportunitiesController@parseCsvImport')->name('opportunities.parseCsvImport');
    Route::post('opportunities/process-csv-import', 'OpportunitiesController@processCsvImport')->name('opportunities.processCsvImport');
    Route::resource('opportunities', 'OpportunitiesController');

      // Ticketcategory
    Route::delete('ticketcategories/destroy', 'TicketcategoryController@massDestroy')->name('ticketcategories.massDestroy');
    Route::post('ticketcategories/parse-csv-import', 'TicketcategoryController@parseCsvImport')->name('ticketcategories.parseCsvImport');
    Route::post('ticketcategories/process-csv-import', 'TicketcategoryController@processCsvImport')->name('ticketcategories.processCsvImport');
    Route::resource('ticketcategories', 'TicketcategoryController');

    // Ticket Description
    Route::delete('ticket-descriptions/destroy', 'TicketDescriptionController@massDestroy')->name('ticket-descriptions.massDestroy');
    Route::post('ticket-descriptions/media', 'TicketDescriptionController@storeMedia')->name('ticket-descriptions.storeMedia');
    Route::post('ticket-descriptions/ckmedia', 'TicketDescriptionController@storeCKEditorImages')->name('ticket-descriptions.storeCKEditorImages');
    Route::post('ticket-descriptions/parse-csv-import', 'TicketDescriptionController@parseCsvImport')->name('ticket-descriptions.parseCsvImport');
    Route::post('ticket-descriptions/process-csv-import', 'TicketDescriptionController@processCsvImport')->name('ticket-descriptions.processCsvImport');
    Route::resource('ticket-descriptions', 'TicketDescriptionController');

    // Ticket Response
    Route::delete('ticket-responses/destroy', 'TicketResponseController@massDestroy')->name('ticket-responses.massDestroy');
    Route::post('ticket-responses/media', 'TicketResponseController@storeMedia')->name('ticket-responses.storeMedia');
    Route::post('ticket-responses/ckmedia', 'TicketResponseController@storeCKEditorImages')->name('ticket-responses.storeCKEditorImages');
    Route::post('ticket-responses/parse-csv-import', 'TicketResponseController@parseCsvImport')->name('ticket-responses.parseCsvImport');
    Route::post('ticket-responses/process-csv-import', 'TicketResponseController@processCsvImport')->name('ticket-responses.processCsvImport');
    Route::resource('ticket-responses', 'TicketResponseController');


    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');





});
// Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
//     // Change password
//     if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
//         Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
//         Route::post('password', 'ChangePasswordController@update')->name('password.update');
//         Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
//         Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
//     }
// });

Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotpassword' ])->name('password.forgotpassword');

Route::post('/forgot-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');


Route::get('/Emailverify', [ForgotPasswordController::class, 'Emailverify' ])->name('Emailverify');
Route::post('/email/send-verification', [ForgotPasswordController::class, 'sendVerificationEmail'])->name('email.send');
Route::get('/email/verify/{token}', [ForgotPasswordController::class, 'verifyEmail'])->name('email.verify');


    // Admin TicketResponse

    Route::get('/ticketresponse', [TicketResponseController::class, 'index'])->name('admin.ticketresponse.index');



/*

Route::group(['prefix' => 'mentor', 'middleware' => ['auth', 'mentor']], function () {
    Route::get('/dashboard', 'MentorDashboardController@index')->name('mentor.dashboard');
});

Route::group(['prefix' => 'mentee', 'middleware' => ['auth', 'mentee']], function () {
    Route::get('/dashboard', 'MenteeDashboardController@index')->name('mentee.dashboard');
});*/

Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Modules
    Route::delete('modules/destroy', 'ModulesController@massDestroy')->name('modules.massDestroy');
    Route::resource('modules', 'ModulesController');

    // Mentors
    Route::delete('mentors/destroy', 'MentorsController@massDestroy')->name('mentors.massDestroy');
    Route::resource('mentors', 'MentorsController');

    // Mentees
    Route::delete('mentees/destroy', 'MenteesController@massDestroy')->name('mentees.massDestroy');
    Route::resource('mentees', 'MenteesController');

    // Sessions
    Route::delete('sessions/destroy', 'SessionsController@massDestroy')->name('sessions.massDestroy');
    Route::resource('sessions', 'SessionsController');

    // Languagespoken
    Route::delete('languagespokens/destroy', 'LanguagespokenController@massDestroy')->name('languagespokens.massDestroy');
    Route::resource('languagespokens', 'LanguagespokenController');

    // Skills
    Route::delete('skills/destroy', 'SkillsController@massDestroy')->name('skills.massDestroy');
    Route::resource('skills', 'SkillsController');

    // Mapping
    Route::delete('mappings/destroy', 'MappingController@massDestroy')->name('mappings.massDestroy');
    Route::resource('mappings', 'MappingController');

    // Survey Form
    Route::delete('survey-forms/destroy', 'SurveyFormController@massDestroy')->name('survey-forms.massDestroy');
    Route::resource('survey-forms', 'SurveyFormController');

    // Guestspeakers
    Route::delete('guestspeakers/destroy', 'GuestspeakersController@massDestroy')->name('guestspeakers.massDestroy');
    Route::resource('guestspeakers', 'GuestspeakersController');

    // Session Recording
    Route::delete('session-recordings/destroy', 'SessionRecordingController@massDestroy')->name('session-recordings.massDestroy');
    Route::post('session-recordings/media', 'SessionRecordingController@storeMedia')->name('session-recordings.storeMedia');
    Route::post('session-recordings/ckmedia', 'SessionRecordingController@storeCKEditorImages')->name('session-recordings.storeCKEditorImages');
    Route::resource('session-recordings', 'SessionRecordingController');

    // Guest Lectures
    Route::delete('guest-lectures/destroy', 'GuestLecturesController@massDestroy')->name('guest-lectures.massDestroy');
    Route::resource('guest-lectures', 'GuestLecturesController');

    // One One Attendance
    Route::delete('one-one-attendances/destroy', 'OneOneAttendanceController@massDestroy')->name('one-one-attendances.massDestroy');
    Route::resource('one-one-attendances', 'OneOneAttendanceController');

    // Guest Session Attendance
    Route::delete('guest-session-attendances/destroy', 'GuestSessionAttendanceController@massDestroy')->name('guest-session-attendances.massDestroy');
    Route::resource('guest-session-attendances', 'GuestSessionAttendanceController');

    // Courses
    Route::delete('courses/destroy', 'CoursesController@massDestroy')->name('courses.massDestroy');
    Route::post('courses/media', 'CoursesController@storeMedia')->name('courses.storeMedia');
    Route::post('courses/ckmedia', 'CoursesController@storeCKEditorImages')->name('courses.storeCKEditorImages');
    Route::resource('courses', 'CoursesController');

    // Lessons
    Route::delete('lessons/destroy', 'LessonsController@massDestroy')->name('lessons.massDestroy');
    Route::post('lessons/media', 'LessonsController@storeMedia')->name('lessons.storeMedia');
    Route::post('lessons/ckmedia', 'LessonsController@storeCKEditorImages')->name('lessons.storeCKEditorImages');
    Route::resource('lessons', 'LessonsController');

    // Tests
    Route::delete('tests/destroy', 'TestsController@massDestroy')->name('tests.massDestroy');
    Route::resource('tests', 'TestsController');

    // Questions
    Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    Route::post('questions/media', 'QuestionsController@storeMedia')->name('questions.storeMedia');
    Route::post('questions/ckmedia', 'QuestionsController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    Route::resource('questions', 'QuestionsController');

    // Question Options
    Route::delete('question-options/destroy', 'QuestionOptionsController@massDestroy')->name('question-options.massDestroy');
    Route::resource('question-options', 'QuestionOptionsController');

    // Test Results
    Route::delete('test-results/destroy', 'TestResultsController@massDestroy')->name('test-results.massDestroy');
    Route::resource('test-results', 'TestResultsController');

    // Test Answers
    Route::delete('test-answers/destroy', 'TestAnswersController@massDestroy')->name('test-answers.massDestroy');
    Route::resource('test-answers', 'TestAnswersController');

    // Assign Tasks
    Route::delete('assign-tasks/destroy', 'AssignTasksController@massDestroy')->name('assign-tasks.massDestroy');
    Route::post('assign-tasks/media', 'AssignTasksController@storeMedia')->name('assign-tasks.storeMedia');
    Route::post('assign-tasks/ckmedia', 'AssignTasksController@storeCKEditorImages')->name('assign-tasks.storeCKEditorImages');
    Route::resource('assign-tasks', 'AssignTasksController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
