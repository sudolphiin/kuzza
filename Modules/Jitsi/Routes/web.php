<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('jitsi')->group(function () {
        Route::get('/', 'JitsiController@index');
    });


    Route::prefix('jitsi')->group(function () {
        Route::name('jitsi.')->middleware('auth')->group(function () {

            Route::get('virtual-class', 'JitsiVirtualClassController@index')->name('virtual-class');
            Route::get('virtual-class/child/{id}', 'JitsiVirtualClassController@myChild')->name('parent.virtual-class')->middleware('userRolePermission:jitsi.parent.virtual-class');
            Route::post('virtual-class/store', 'JitsiVirtualClassController@store')->name('virtual-class.store')->middleware('userRolePermission:jitsi.virtual-class.store');
            Route::delete('virtual_class/{id}', 'JitsiVirtualClassController@destroy')->name('virtual_class.destroy')->middleware('userRolePermission:jitsi.virtual-class.destroy');
            Route::get('virtual-class-show/{id}', 'JitsiVirtualClassController@show')->name('virtual-class.show');
            Route::get('virtual-class-edit/{id}', 'JitsiVirtualClassController@edit')->name('virtual-class.edit');
            Route::post('virtual-class/{id}', 'JitsiVirtualClassController@update')->name('virtual-class.update')->middleware('userRolePermission:jitsi.virtual-class.edit');


            Route::get('meetings', 'JitsiMeetingController@index')->name('meetings');
            Route::get('meetings/parent', 'JitsiMeetingController@index')->name('parent.meetings')->middleware('userRolePermission:jitsi.parent.meetings');
            Route::post('meetings', 'JitsiMeetingController@store')->name('meetings.store')->middleware('userRolePermission:jitsi.meetings.store');
            Route::get('meetings-show/{id}', 'JitsiMeetingController@show')->name('meetings.show');
            Route::get('meetings-edit/{id}', 'JitsiMeetingController@edit')->name('meetings.edit');
            Route::post('meetings/{id}', 'JitsiMeetingController@update')->name('meetings.update')->middleware('userRolePermission:jitsi.meetings.edit');
            Route::delete('meetings/{id}', 'JitsiMeetingController@destroy')->name('meetings.destroy')->middleware('userRolePermission:jitsi.meetings.destroy');

            Route::get('user-list-user-type-wise', 'JitsiMeetingController@userWiseUserList')->name('user.list.user.type.wise');
            Route::get('virtual-class-room/{id}', 'JitsiMeetingController@meetingStart')->name('meeting.join');


            Route::get('meeting-start/{id}', 'JitsiMeetingController@meetingStart')->name('meeting.start');
            Route::get('meeting-join/{id}', 'JitsiMeetingController@meetingJoin')->name('meeting.join');


            Route::get('class-start/{id}', 'JitsiVirtualClassController@classStart')->name('class.start');
            Route::get('class-join/{id}', 'JitsiVirtualClassController@classJoin')->name('class.join');

            Route::get('settings', 'JitsiSettingController@settings')->name('settings')->middleware('userRolePermission:jitsi.settings');
            Route::post('settings', 'JitsiSettingController@updateSettings')->name('settings.update')->middleware('userRolePermission:jitsi.settings.update');


            Route::get('virtual-class-reports', 'JitsiReportController@index')->name('virtual.class.reports.show')->middleware('userRolePermission:jitsi.virtual.class.reports.show');
            Route::get('meeting-reports', 'JitsiReportController@meetingReport')->name('meeting.reports.show')->middleware('userRolePermission:jitsi.meeting.reports.show');


        });
    });
});

