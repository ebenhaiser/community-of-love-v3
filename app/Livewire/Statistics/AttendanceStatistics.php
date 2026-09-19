<?php

namespace App\Livewire\Statistics;

use App\Models\Activity;
use App\Models\Cool;
use App\Models\Member;
use App\Models\MemberFollowUp;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Statistik & Laporan Kehadiran')]
class AttendanceStatistics extends Component
{
    public string $coolFilter = '';

    public string $startDate = '';

    public string $endDate = '';

    public int $consecutiveThreshold = 2; // Flag members absent >= 2 consecutive times

    public function mount(): void
    {
        $this->startDate = now()->subMonths(2)->startOfMonth()->toDateString();
        $this->endDate = now()->toDateString();
    }

    public function render()
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->orderBy('name')
            ->get();

        $shepherdCoolIds = $cools->pluck('cool_id')->toArray();

        // Query activities in period
        $activities = Activity::where('is_deleted', false)
            ->whereBetween('activity_date', [$this->startDate, $this->endDate])
            ->when($isShepherd, fn ($q) => $q->whereIn('cool_id', $shepherdCoolIds))
            ->when($this->coolFilter, fn ($q) => $q->where('cool_id', $this->coolFilter))
            ->with(['cool.shepherd', 'activityType', 'attendances.status'])
            ->orderByDesc('activity_date')
            ->get();

        $totalActivities = $activities->count();
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalExcused = 0;
        $totalSick = 0;

        $activityBreakdown = [];

        foreach ($activities as $act) {
            $presentCount = $act->attendances->where('is_deleted', false)->where('status.code', 'PRESENT')->count();
            $absentCount = $act->attendances->where('is_deleted', false)->where('status.code', 'ABSENT')->count();
            $excusedCount = $act->attendances->where('is_deleted', false)->where('status.code', 'EXCUSED')->count();
            $sickCount = $act->attendances->where('is_deleted', false)->where('status.code', 'SICK')->count();
            $totalCount = $presentCount + $absentCount + $excusedCount + $sickCount;

            $totalPresent += $presentCount;
            $totalAbsent += $absentCount;
            $totalExcused += $excusedCount;
            $totalSick += $sickCount;

            $rate = $totalCount > 0 ? round(($presentCount / $totalCount) * 100) : 0;

            $activityBreakdown[] = [
                'activity_id' => $act->activity_id,
                'name' => $act->name,
                'date' => $act->activity_date ? $act->activity_date->format('d M Y') : '-',
                'cool_name' => $act->cool->name ?? '-',
                'present' => $presentCount,
                'absent' => $absentCount,
                'excused' => $excusedCount,
                'sick' => $sickCount,
                'total' => $totalCount,
                'rate' => $rate,
            ];
        }

        $grandTotal = $totalPresent + $totalAbsent + $totalExcused + $totalSick;
        $overallRate = $grandTotal > 0 ? round(($totalPresent / $grandTotal) * 100) : 0;

        // Consecutive Absence Detection
        // Exclude members who have already been handled/resolved in pastoral follow-up
        $resolvedMemberIds = MemberFollowUp::where('status', 'RESOLVED')
            ->where('is_deleted', false)
            ->pluck('member_id')
            ->toArray();

        // Find members of selected COOL(s) (do not exclude inactive members, as consecutive absentees often become inactive and need pastoral follow-up)
        $membersQuery = Member::where('is_deleted', false)
            ->whereNotIn('member_id', $resolvedMemberIds)
            ->with([
                'cools' => fn ($q) => $q->wherePivot('is_deleted', false)->where('cools.is_deleted', false)->with('shepherd'),
                'coolMembers.cool.shepherd',
                'attendances' => function ($q) {
                    $q->where('attendances.is_deleted', false)
                        ->whereHas('activity', fn ($a) => $a->where('activities.is_deleted', false))
                        ->with(['activity', 'status']);
                },
            ]);

        if ($isShepherd) {
            $membersQuery->whereHas('coolMembers', function ($q) use ($shepherdCoolIds) {
                $q->whereIn('cool_id', $shepherdCoolIds)->where('cool_members.is_deleted', false);
            });
        }

        if ($this->coolFilter) {
            $membersQuery->whereHas('coolMembers', function ($q) {
                $q->where('cool_id', $this->coolFilter)->where('cool_members.is_deleted', false);
            });
        }

        $allMembers = $membersQuery->get();
        $absentAlerts = [];

        foreach ($allMembers as $member) {
            // Check past attendances ordered by activity date desc
            $sortedAttendances = $member->attendances->sortByDesc(
                fn ($att) => $att->activity?->activity_date?->timestamp ?? ($att->attendance_time ? $att->attendance_time->timestamp : $att->attendance_id)
            );

            $consecutiveCount = 0;
            $lastAttendedDate = null;

            foreach ($sortedAttendances as $att) {
                $statusCode = $att->status->code ?? '';
                if ($statusCode === 'ABSENT') {
                    $consecutiveCount++;
                } elseif ($statusCode === 'PRESENT') {
                    $lastAttendedDate = $att->activity->activity_date ?? null;
                    break;
                }
            }

            if ($consecutiveCount >= $this->consecutiveThreshold) {
                $cool = $member->cools->first() ?? $member->coolMembers->first()?->cool;
                $absentAlerts[] = [
                    'member_id' => $member->member_id,
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'cool_name' => $cool->name ?? '-',
                    'shepherd_name' => $cool?->shepherd?->name ?? '-',
                    'consecutive_absent' => $consecutiveCount,
                    'last_attended' => $lastAttendedDate ? $lastAttendedDate->format('d M Y') : 'Belum pernah',
                ];
            }
        }

        return view('livewire.statistics.attendance-statistics', compact(
            'cools',
            'totalActivities',
            'totalPresent',
            'totalAbsent',
            'totalExcused',
            'totalSick',
            'overallRate',
            'activityBreakdown',
            'absentAlerts',
            'isShepherd'
        ));
    }
}
