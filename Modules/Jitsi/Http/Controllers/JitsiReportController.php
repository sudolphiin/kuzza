<?php

namespace Modules\Jitsi\Http\Controllers;
use App\User;
use App\SmClass;
use App\SmStaff;
use App\YearCheck;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Jitsi\Entities\JitsiMeeting;
use Modules\Jitsi\Entities\JitsiVirtualClass;
use Modules\RolePermission\Entities\InfixRole;

class JitsiReportController extends Controller
{
   
    public function index(Request $request)
    {
            try {
                $data['classes'] = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $data['teachers'] = SmStaff::where('active_status', 1)->where(function($q)  {
	            $q->where('role_id', 4)->orWhere('previous_role_id', 4);})->where('school_id', Auth::user()->school_id)->get();
                
                if(moduleStatusCheck('University')) {
                    if (auth()->user()->role_id == 4) {
                        $data = $this->setSearchKeywordData($data, $request);
                        $data['meetings'] = $this->virtaulClassSearchTeacher($request);
                    } elseif (auth()->user()->role_id == 1 || userPermission('g-meet.virtual.class.reports.show')) {
                        $data = $this->setSearchKeywordData($data, $request);
                        $data['meetings'] = $this->virtaulClassSearchAdmin($request);
                    } else {
                        Toastr::error('Your are not authorized!', 'Failed');
                        return redirect()->back()->send();
                    } 
                }
                else if ($request->has('class_id')) {
                    if (Auth::user()->role_id == 4) {
                        $data = $this->setSearchKeywordData($data, $request);
                        $data['meetings'] = $this->virtaulClassSearchTeacher($request);
                    } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                        $data = $this->setSearchKeywordData($data, $request);
                        $data['meetings'] = $this->virtaulClassSearchAdmin($request);
                    } else {
                        Toastr::error('Your are not authorized!', 'Failed');
                        return redirect()->back();
                    }
                }
            
            return view('jitsi::report.report',$data);
            
        } catch (Exception $e) {
            
        }
    }


    public function meetingReport(Request $request){
        try {
            $data['roles'] = InfixRole::where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->whereNotIn('id', [1, 2])->get();
            if ($request->has('member_type')) {
                if (Auth::user()->role_id != 1) {
                    $data['meetings'] = $this->meetingSearchOthers($request);
                } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                    $data['meetings'] = $this->meetingSearchAdmin($request);
                } else {
                    Toastr::error('Your are not authorized!', 'Failed');
                    return redirect()->back();
                }
                $data = $this->setSearchKeywordDataMeeting($data, $request);
            }
            return view('jitsi::report.meeting_reports',$data);
            
        } catch (\Exception $e) {
            
        }
    }   
  private function meetingSearchAdmin($request)
    {
        $from_time =  Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time =  Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = JitsiMeeting::query();
        $query->with('participates');

        if ($request->has('member_ids')) {
            $query->whereHas('participates', function ($qry) use ($request) {
                return $qry->whereIn('user_id', $request['member_ids']);
            });
        }
        if (!$request->has('member_ids')) {
            $UserIDList = User::where('role_id', $request['member_type'])->where('school_id', Auth::user()->school_id)->pluck('id');
            $query->whereHas('participates', function ($qry) use ($UserIDList) {
                return $qry->whereIn('user_id', $UserIDList);
            });
        }
        if ($request->has('teachser_ids')) {
            $query->whereHas('participates', function ($qry) use ($request) {
                return $qry->where('user_id', $request->teachser_ids);
            });
        }

        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->paginate(10);
    }

    private function meetingSearchOthers($request)
    {
        $from_time =  Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time =  Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = JitsiMeeting::query();
        $query->with('participates');

        if ($request->has('member_ids')) {
            $query->whereHas('participates', function ($qry) use ($request) {
                return $qry->whereIn('user_id', $request['member_ids']);
            });
        }
        if ($request->has('teachser_ids')) {
            $query->whereHas('participates', function ($qry) use ($request) {
                return $qry->where('user_id', $request->teachser_ids);
            });
        }
        if (!$request->has('member_ids')) {
            $UserIDList = User::where('role_id', $request['member_type'])->where('school_id', Auth::user()->school_id)->pluck('id');
            $query->whereHas('participates', function ($qry) use ($UserIDList) {
                return $qry->whereIn('user_id', $UserIDList);
            });
        }

        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->get();
    }


    public  function universityDataSearch($request) {
        $query = JitsiVirtualClass::query();

        $query->when($request->has('un_session_id') && $request['un_session_id'] != null, function ($q) use ($request) {
            return $q->where('un_session_id', $request['un_session_id']);
        });
        $query->when($request->has('un_academic_id') && $request['un_academic_id'] != null, function ($q) use ($request) {
            return $q->where('un_academic_id', $request['un_academic_id']);
        });
        $query->when($request->has('un_semester_id') && $request['un_semester_id'] != null, function ($q) use ($request) {
            return $q->where('un_semester_id', $request['un_semester_id']);
        });
        $query->when($request->has('un_department_id') && $request['un_department_id'] != null, function ($q) use ($request) {
            return $q->where('un_department_id', $request['un_department_id']);
        });
        $query->when($request->has('un_faculty_id') && $request['un_faculty_id'] != null, function ($q) use ($request) {
            return $q->where('un_faculty_id', $request['un_faculty_id']);
        });
        $query->when($request->has('un_semester_label_id') && $request['un_semester_label_id'] != null, function ($q) use ($request) {
            return $q->where('un_semester_label_id', $request['un_semester_label_id']);
        });
        $query->when($request->has('un_section_id') && $request['un_section_id'] != null, function ($q) use ($request) {
            return $q->where('un_section_id', $request['un_section_id']);
        });

        return $query;
    }

    private function virtaulClassSearchTeacher($request)
    {
        $from_time =  Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time =  Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = JitsiVirtualClass::query();
        if(moduleStatusCheck('University')) {
            $this->universityDataSearch($request);
        } else {
            // $query->when($request->has('shift') && $request['shift'] != null, function ($q) use ($request) {
            //     return $q->where('shift_id', $request['shift']);
            // });
            
            $query->when($request->has('class_id') && $request['class_id'] != null, function ($q) use ($request) {
                return $q->where('class_id', $request['class_id']);
            });
            $query->when($request->has('section_id') && $request['section_id'] != null, function ($q) use ($request) {
                return $q->where('section_id', $request['section_id']);
            });
        }

        $query->when($request->has('teachser_ids') && $request['teachser_ids'] != null, function ($q) {
            $q->whereHas('teachers', function ($qry) {
                return $qry->where('user_id', Auth::user()->id);
            });
        });
        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->get();
    }

    private function virtaulClassSearchAdmin($request)
        {
            $from_time =  Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
            $to_time =  Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

            $query = JitsiVirtualClass::query();

            if(moduleStatusCheck('University')) {
                $this->universityDataSearch($request);
            } else {
                // $query->when($request->has('shift') && $request['shift'] != null, function ($q) use ($request) {
                //     return $q->where('shift_id', $request['shift']);
                // });
                $query->when($request->has('class_id') && $request['class_id'] != null, function ($q) use ($request) {
                    return $q->where('class_id', $request['class_id']);
                });
                $query->when($request->has('section_id') && $request['section_id'] != null, function ($q) use ($request) {
                    return $q->where('section_id', $request['section_id']);
                });
            }

            $query->when($request->has('teacher_id') && $request['teacher_id'] != null, function ($q) use ($request) {
                $q->whereHas('teachers', function ($qry) use ($request) {
                    return $qry->whereIn('user_id', [$request['teacher_id']]);
                });
            });
            $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
                return $q->whereBetween('start_time', [$from_time, $to_time]);
            });
            return $query->get();
        }

    private function  setSearchKeywordData($data, $request)
    {
        if(moduleStatusCheck('University')) {
            $data['un_session_id'] = $request['un_session_id'];
            $data['un_academic_id'] = $request['un_academic_id'];
            $data['un_semester_id'] = $request['un_semester_id'];
            $data['un_department_id'] = $request['un_department_id'];
            $data['un_faculty_id'] = $request['un_faculty_id'];
            $data['un_semester_label_id'] = $request['un_semester_label_id'];
            $data['un_section_id'] = $request['un_section_id'];
        }else {
            // $data['shift_id']       = $request['shift'] ?? null;
            $data['class_id']       = $request['class_id'];
            $data['section_id']     = $request['section_id'];
        }

        $data['teacher_id']     = $request['teacher_id'];
        $data['from_time']      = $request['from_time'];
        $data['to_time']        = $request['to_time'];
        return $data;
    }

    private function  setSearchKeywordDataMeeting($data, $request)
    {
        $data['member_type']    = $request['member_type'];
        $data['member_ids']     = $request['member_ids'];
        $data['from_time']      = $request['from_time'];
        $data['to_time']        = $request['to_time'];
        return $data;
    }

}
