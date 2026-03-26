<?php

namespace App\Http\Controllers;

use App\SmAboutPage;
use App\SmContactPage;
use App\SmCourse;
use App\SmEvent;
use App\SmHomePageSetting;
use App\SmNews;
use App\SmNoticeBoard;
use App\SmStaff;
use App\SmStudent;
use App\SmTestimonial;
use Brian2694\Toastr\Facades\Toastr;
use Exception;

class LandingController extends Controller
{
    public function landing()
    {
        return view('frontEnd.landing.original');
    }

    public function index()
    {
        try {
            $schoolId = $this->resolveSchoolId();
            $homePage = SmHomePageSetting::where('school_id', $schoolId)->first();
            $news = SmNews::where('school_id', $schoolId)->where('mark_as_archive', 0)->orderBy('order', 'asc')->limit(3)->get();
            $courses = SmCourse::where('school_id', $schoolId)->orderBy('id', 'asc')->limit(3)->get();
            $events = SmEvent::where('school_id', $schoolId)->orderBy('from_date', 'desc')->limit(4)->get();
            $testimonial = SmTestimonial::where('school_id', $schoolId)->get();
            $contact_info = SmContactPage::where('school_id', $schoolId)->first();
            $about = SmAboutPage::where('school_id', $schoolId)->first();
            $totalStudents = SmStudent::where('active_status', 1)->where('school_id', $schoolId)->count();
            $totalTeachers = SmStaff::where('active_status', 1)
                ->where(function ($q): void {
                    $q->where('role_id', 4)->orWhere('previous_role_id', 4);
                })
                ->where('school_id', $schoolId)
                ->count();
            $courseCount = SmCourse::where('school_id', $schoolId)->count();
            $notice_board = SmNoticeBoard::where('publish_on', '<=', date('Y-m-d'))
                ->where('school_id', $schoolId)
                ->where('is_published', 1)
                ->orderBy('created_at', 'DESC')
                ->take(3)
                ->get();

            return view('frontEnd.landing.new', compact(
                'homePage',
                'news',
                'courses',
                'events',
                'testimonial',
                'contact_info',
                'about',
                'totalStudents',
                'totalTeachers',
                'courseCount',
                'notice_board'
            ));
        } catch (Exception $e) {
            Toastr::error('Unable to load landing page', 'Failed');

            return view('frontEnd.landing.original');
        }
    }

    private function resolveSchoolId(): int
    {
        if (app()->bound('school') && app('school')) {
            return app('school')->id;
        }

        return optional(SmHomePageSetting::select('school_id')->first())->school_id ?? 1;
    }
}
