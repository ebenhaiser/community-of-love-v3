<?php

namespace App\Livewire;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Cool;
use App\Models\Member;
use App\Models\MemberFollowUp;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard Utama')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->member_id;

        $coolQuery = Cool::where('is_deleted', false)->where('is_active', true);
        if ($isShepherd) {
            $coolQuery->where('shepherd_id', $user->member_id);
        }
        $cools = $coolQuery->with(['shepherd', 'members'])->get();
        $coolIds = $cools->pluck('cool_id');

        $totalCools = $cools->count();
        $totalShepherds = $isShepherd ? 1 : Cool::where('is_deleted', false)->where('is_active', true)->distinct('shepherd_id')->count('shepherd_id');

        $memberQuery = Member::where('is_deleted', false);
        if ($isShepherd) {
            $memberQuery->whereHas('coolMembers', function ($q) use ($coolIds) {
                $q->whereIn('cool_id', $coolIds)->where('is_deleted', false);
            });
        }
        $totalMembers = (clone $memberQuery)->count();
        $inCoolMembers = (clone $memberQuery)->where('is_in_cool', true)->count();
        $notInCoolMembers = (clone $memberQuery)->where('is_in_cool', false)->count();

        $activityQuery = Activity::where('is_deleted', false);
        if ($isShepherd) {
            $activityQuery->whereIn('cool_id', $coolIds);
        }
        $totalActivities = $activityQuery->count();

        // Recent activities
        $recentActivities = (clone $activityQuery)
            ->with(['cool', 'activityType'])
            ->orderByDesc('activity_date')
            ->take(6)
            ->get();

        // Attendance stats
        $attendanceQuery = Attendance::where('is_deleted', false);
        if ($isShepherd) {
            $attendanceQuery->whereHas('activity', function ($q) use ($coolIds) {
                $q->whereIn('cool_id', $coolIds);
            });
        }
        $totalAttendanceRecords = (clone $attendanceQuery)->count();
        $presentCount = (clone $attendanceQuery)->whereHas('status', fn ($q) => $q->where('code', 'PRESENT'))->count();
        $absentCount = (clone $attendanceQuery)->whereHas('status', fn ($q) => $q->where('code', 'ABSENT'))->count();
        $excusedCount = (clone $attendanceQuery)->whereHas('status', fn ($q) => $q->where('code', 'EXCUSED'))->count();
        $sickCount = (clone $attendanceQuery)->whereHas('status', fn ($q) => $q->where('code', 'SICK'))->count();

        $attendanceRate = $totalAttendanceRecords > 0
            ? round(($presentCount / $totalAttendanceRecords) * 100, 1)
            : 0;

        // Exclude members who have already been handled/resolved in pastoral follow-up
        $resolvedMemberIds = MemberFollowUp::where('status', 'RESOLVED')
            ->where('is_deleted', false)
            ->pluck('member_id')
            ->toArray();

        // Members with consecutive absences (FR-11 & FR-12 alert)
        $flaggedMembers = Member::where('is_deleted', false)
            ->whereNotIn('member_id', $resolvedMemberIds)
            ->when($isShepherd, function ($q) use ($coolIds) {
                $q->whereHas('coolMembers', fn ($cm) => $cm->whereIn('cool_id', $coolIds));
            })
            ->with(['coolMembers.cool', 'attendances' => function ($q) {
                $q->where('is_deleted', false)->with('status')->latest('attendance_id')->take(4);
            }])
            ->get()
            ->filter(function ($member) {
                $recent = $member->attendances;
                if ($recent->count() >= 2) {
                    $absentCount = $recent->filter(fn ($a) => $a->status && $a->status->code === 'ABSENT')->count();

                    return $absentCount >= 2;
                }

                return false;
            })
            ->map(function ($member) {
                $member->cool_name = $member->coolMembers->first()?->cool?->name ?? 'COOL';

                return $member;
            })
            ->take(5);

        return view('livewire.dashboard', compact(
            'totalCools',
            'totalShepherds',
            'totalMembers',
            'inCoolMembers',
            'notInCoolMembers',
            'totalActivities',
            'attendanceRate',
            'presentCount',
            'absentCount',
            'excusedCount',
            'sickCount',
            'cools',
            'recentActivities',
            'flaggedMembers'
        ));
    }
}
