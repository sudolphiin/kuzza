<?php

namespace Modules\Jitsi\Entities;

// use App\Models\Shift;
use App\User;
use App\SmStaff;
use App\SmClass;
use Carbon\Carbon;
use App\SmSection;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\University\Entities\UnAcademicYear;
use Modules\University\Entities\UnDepartment;
use Modules\University\Entities\UnSemesterLabel;

class JitsiVirtualClass extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'jitsi_virtual_classes';

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'jitsi_virtual_class_teachers', 'meeting_id', 'user_id');
    }

    public function class()
    {
        return $this->hasOne(SmClass::class, 'id', 'class_id')->withDefault();
    }

    public function section()
    {
        return $this->hasOne(SmSection::class, 'id', 'section_id')->withDefault();
    }

    public function getTeachersNameAttribute()
    {
        return implode(', ', $this->teachers->pluck('full_name')->toArray());
    }

    public function getMeetingDateTimeAttribute()
    {
        return Carbon::parse($this->date_of_meeting . ' ' . $this->time_of_meeting)->format('m-d-Y h:i A');
    }

    public function getCurrentStatusAttribute()
    {
        $GSetting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
         date_default_timezone_set($GSetting->timeZone->time_zone);
        $now = Carbon::now()->setTimezone($GSetting->timeZone->time_zone);
        if($this->time_start_before==null){
            $before_start=10;
        }else{
            $before_start=$this->time_start_before;
        }

        if ($this->is_recurring == 1) {
            if ($now->between(Carbon::parse($this->start_time)->addMinute(-$before_start)->format('Y-m-d H:i:s'), Carbon::parse($this->recurring_end_date)->endOfDay()->format('Y-m-d H:i:s'))) {
                return 'started';
              
            }
            if (!$now->gt(Carbon::parse($this->recurring_end_date)->addMinute(-$before_start))) {
                return 'waiting';
            }
            return 'closed';
        } else {
            if ($now->between(Carbon::parse($this->start_time)->addMinute(-$before_start)->format('Y-m-d H:i:s'), Carbon::parse($this->end_time)->format('Y-m-d H:i:s'))) {
                return 'started';
            }

            if (!$now->gt(Carbon::parse($this->end_time)->addMinute(-$before_start))) {
                return 'waiting';
            }
            return 'closed';
        }
    }

    public function getMeetingEndTimeAttribute()
    {
        return Carbon::parse($this->date_of_meeting . ' ' . $this->time_of_meeting)->addMinute($this->meeting_duration);
    }

    public function getUrlAttribute()
    {
        if (Auth::user()->role_id == 4 || Auth::user()->role_id == 1) {
            return 'https://zoom.us/wc/' . $this->meeting_id . '/start';
        } else {
            return 'https://zoom.us/wc/' . $this->meeting_id . '/join';
        }
    }

    // public function shift()
    // {
    //     return $this->belongsTo(Shift::class, 'shift_id');
    // }

    public  function department():BelongsTo
    {
        return $this->belongsTo(UnDepartment::class, 'un_department_id', 'id')->withDefault();
    }

    public function semesterLabel():BelongsTo
    {
        return $this->belongsTo(UnSemesterLabel::class, 'un_semester_label_id', 'id')->withDefault();
    }

    public function unAcademic():BelongsTo
    {
        return $this->belongsTo(UnAcademicYear::class, 'un_academic_id', 'id');
    }

    public function unSection():BelongsTo
    {
        return $this->belongsTo(SmSection::class, 'un_section_id', 'id')->withDefault(['section_name'=>'All Sections']);
    }

}
