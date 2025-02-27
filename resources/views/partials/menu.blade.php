<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
           {{-- {{ trans('panel.site_title') }} --}}
           <img src="https://forstubucket1.s3.ap-south-1.amazonaws.com/logos/TUC+Blue+Transparent.png" 
                alt="TUC Blue Transparent Logo" 
                style="max-width: 100%; height: auto;">
           <!-- TransUnion Cibil -->
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('one_one_session_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/sessions*") ? "c-show" : "" }} {{ request()->is("admin/one-one-attendances*") ? "c-show" : "" }} {{ request()->is("admin/assign-tasks*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.oneOneSession.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('session_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sessions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sessions") || request()->is("admin/sessions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-calendar-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.session.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('one_one_attendance_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.one-one-attendances.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/one-one-attendances") || request()->is("admin/one-one-attendances/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-paperclip c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.oneOneAttendance.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('assign_task_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.assign-tasks.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/assign-tasks") || request()->is("admin/assign-tasks/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-tasks c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.assignTask.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('guest_session_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/guestspeakers*") ? "c-show" : "" }} {{ request()->is("admin/guest-lectures*") ? "c-show" : "" }} {{ request()->is("admin/guest-session-attendances*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-address-book c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.guestSession.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('guestspeaker_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.guestspeakers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/guestspeakers") || request()->is("admin/guestspeakers/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.guestspeaker.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('guest_lecture_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.guest-lectures.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/guest-lectures") || request()->is("admin/guest-lectures/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-chalkboard-teacher c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.guestLecture.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('guest_session_attendance_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.guest-session-attendances.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/guest-session-attendances") || request()->is("admin/guest-session-attendances/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-paperclip c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.guestSessionAttendance.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('survey_form_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.survey-forms.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/survey-forms") || request()->is("admin/survey-forms/*") ? "c-active" : "" }}">
                    <i class="fa-fw far fa-question-circle c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.surveyForm.title') }}
                </a>
            </li>
        @endcan
        @can('master_data_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/mentors*") ? "c-show" : "" }} {{ request()->is("admin/mentees*") ? "c-show" : "" }} {{ request()->is("admin/languagespokens*") ? "c-show" : "" }} {{ request()->is("admin/skills*") ? "c-show" : "" }} {{ request()->is("admin/mappings*") ? "c-show" : "" }} {{ request()->is("admin/session-recordings*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.masterData.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('mentor_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.mentors.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/mentors") || request()->is("admin/mentors/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-calendar-check c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.mentor.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('mentee_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.mentees.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/mentees") || request()->is("admin/mentees/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-graduate c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.mentee.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('languagespoken_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.languagespokens.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/languagespokens") || request()->is("admin/languagespokens/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.languagespoken.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('skill_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.skills.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/skills") || request()->is("admin/skills/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.skill.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('mapping_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.mappings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/mappings") || request()->is("admin/mappings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.mapping.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('session_recording_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.session-recordings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/session-recordings") || request()->is("admin/session-recordings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-video c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.sessionRecording.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('modules_master_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/modules*") ? "c-show" : "" }} {{ request()->is("admin/chapters*") ? "c-show" : "" }} {{ request()->is("admin/courses*") ? "c-show" : "" }} {{ request()->is("admin/lessons*") ? "c-show" : "" }} {{ request()->is("admin/tests*") ? "c-show" : "" }} {{ request()->is("admin/questions*") ? "c-show" : "" }} {{ request()->is("admin/question-options*") ? "c-show" : "" }} {{ request()->is("admin/test-results*") ? "c-show" : "" }} {{ request()->is("admin/test-answers*") ? "c-show" : "" }} {{ request()->is("admin/chapter-tests*") ? "c-show" : "" }} {{ request()->is("admin/subchapters*") ? "c-show" : "" }} {{ request()->is("admin/moduleresourcebanks*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-atlas c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.modulesMaster.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('module_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.modules.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/modules") || request()->is("admin/modules/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-book c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.module.title') }}
                            </a>
                        </li>
                    @endcan
                        
                    @can('chapter_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.chapters.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/chapters") || request()->is("admin/chapters/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.chapter.title') }}
                            </a>
                        </li>
                    @endcan
                    {{--@can('course_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.courses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/courses") || request()->is("admin/courses/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.course.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('lesson_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.lessons.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/lessons") || request()->is("admin/lessons/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.lesson.title') }}
                            </a>
                        </li>
                    @endcan--}}
                    @can('test_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tests") || request()->is("admin/tests/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.test.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('question_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.questions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/questions") || request()->is("admin/questions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.question.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('question_option_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.question-options.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/question-options") || request()->is("admin/question-options/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.questionOption.title') }}
                            </a>
                        </li>
                    @endcan
                    {{--@can('test_result_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.test-results.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/test-results") || request()->is("admin/test-results/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.testResult.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('test_answer_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.test-answers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/test-answers") || request()->is("admin/test-answers/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.testAnswer.title') }}
                            </a>
                        </li>
                    @endcan--}}
                    {{--@can('chapter_test_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.chapter-tests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/chapter-tests") || request()->is("admin/chapter-tests/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.chapterTest.title') }}
                            </a>
                        </li>
                    @endcan--}}
                    @can('subchapter_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.subchapters.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/subchapters") || request()->is("admin/subchapters/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.subchapter.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('moduleresourcebank_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.moduleresourcebanks.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/moduleresourcebanks") || request()->is("admin/moduleresourcebanks/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.moduleresourcebank.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/modules*") ? "c-show" : "" }} {{ request()->is("admin/chapters*") ? "c-show" : "" }} {{ request()->is("admin/courses*") ? "c-show" : "" }} {{ request()->is("admin/lessons*") ? "c-show" : "" }} {{ request()->is("admin/tests*") ? "c-show" : "" }} {{ request()->is("admin/questions*") ? "c-show" : "" }} {{ request()->is("admin/question-options*") ? "c-show" : "" }} {{ request()->is("admin/test-results*") ? "c-show" : "" }} {{ request()->is("admin/test-answers*") ? "c-show" : "" }} {{ request()->is("admin/chapter-tests*") ? "c-show" : "" }} {{ request()->is("admin/subchapters*") ? "c-show" : "" }} {{ request()->is("admin/moduleresourcebanks*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa fa-file-text c-sidebar-nav-icon" aria-hidden="true"></i>
                    Export Data
                </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a href="{{route('admin.progressData.masterdata')}}" class="c-sidebar-nav-link">
                        <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                        Overall Master Data
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a href="{{route('admin.progressData.modulereport')}}" class="c-sidebar-nav-link">
                        <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                        Overall Module Report
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a href="{{route('admin.progressData.mastersessionreport')}}" class="c-sidebar-nav-link">
                        <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                        Master Session Data
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a href="{{route('admin.mentorwise.module.mentorwisemodule')}}" class="c-sidebar-nav-link">
                        <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                        Mentorwise Session Data
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a href="{{route('admin.quizCompletionReport')}}" class="c-sidebar-nav-link">
                        <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                        Quiz Completion Report
                    </a>
                </li>
            </ul>
        </li>
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/audit-logs*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audit-logs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        {{--@can('create_progress_table_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.create-progress-tables.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/create-progress-tables") || request()->is("admin/create-progress-tables/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.createProgressTable.title') }}
                </a>
            </li>
        @endcan--}}
        @can('opportunity_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.opportunities.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/opportunities") || request()->is("admin/opportunities/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.opportunity.title') }}
                </a>
            </li>
        @endcan
        @can('support_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/ticketcategories*") ? "c-show" : "" }} {{ request()->is("admin/ticket-descriptions*") ? "c-show" : "" }} {{ request()->is("admin/ticket-responses*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-headset c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.support.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('ticketcategory_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ticketcategories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ticketcategories") || request()->is("admin/ticketcategories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ticketcategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('ticket_description_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ticket-descriptions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ticket-descriptions") || request()->is("admin/ticket-descriptions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ticketDescription.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('ticket_response_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ticket-responses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ticket-responses") || request()->is("admin/ticket-responses/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ticketResponse.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.systemCalendar") }}" class="c-sidebar-nav-link {{ request()->is("admin/system-calendar") || request()->is("admin/system-calendar/*") ? "c-active" : "" }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-calendar">

                </i>
                {{ trans('global.systemCalendar') }}
            </a>
        </li>
        @php($unread = \App\QaTopic::unreadCount())
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.messenger.index") }}" class="{{ request()->is("admin/messenger") || request()->is("admin/messenger/*") ? "c-active" : "" }} c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fa-fw fa fa-envelope">

                    </i>
                    <span>{{ trans('global.messages') }}</span>
                    @if($unread > 0)
                        <strong>( {{ $unread }} )</strong>
                    @endif

                </a>
            </li>
            @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                @can('profile_password_edit')
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('password.forgotpassword') }}">
                            <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                            </i>
                            {{ trans('global.change_password') }}
                        </a>
                    </li>
                @endcan
            @endif
            <li class="c-sidebar-nav-item">
                <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
    </ul>

</div>