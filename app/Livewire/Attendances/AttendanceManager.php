<?php

namespace App\Livewire\Attendances;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Cool;
use App\Models\CoolMember;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Presensi & Absensi Kegiatan COOL')]
class AttendanceManager extends Component
{
    #[Url]
    public ?int $activity_id = null;

    public string $coolFilter = '';

    public array $notes = [];

    public function mount(?int $activityId = null): void
    {
        if ($activityId) {
            $this->activity_id = $activityId;
        }

        if (! $this->activity_id) {
            // Find most recent activity for the user
            $user = Auth::user();
            $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

            $recent = Activity::where('is_deleted', false)
                ->when($isShepherd, function ($q) use ($user) {
                    $q->whereHas('cool', fn ($c) => $c->where('shepherd_id', $user->shepherd_id));
                })
                ->orderByDesc('activity_date')
                ->first();

            if ($recent) {
                $this->activity_id = $recent->activity_id;
            }
        }

        $this->authorizeActivity();
        $this->loadNotes();
    }

    public function updatedActivityId(): void
    {
        $this->authorizeActivity();
        $this->loadNotes();
    }

    protected function authorizeActivity(): void
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        if ($this->activity_id && $isShepherd) {
            $act = Activity::with('cool')->find($this->activity_id);
            if ($act && $act->cool && $act->cool->shepherd_id !== $user->shepherd_id) {
                abort(403, 'Anda hanya dapat mengakses presensi kegiatan kelompok COOL Anda sendiri.');
            }
        }
    }

    public function loadNotes(): void
    {
        $this->notes = [];
        if ($this->activity_id) {
            $attendances = Attendance::where('activity_id', $this->activity_id)
                ->where('is_deleted', false)
                ->get();

            foreach ($attendances as $att) {
                $this->notes[$att->member_id] = $att->note ?? '';
            }
        }
    }

    public function setStatus(int $memberId, int $statusId): void
    {
        $this->authorizeActivity();

        if (! $this->activity_id) {
            return;
        }

        $activity = Activity::with('cool')->find($this->activity_id);
        if (! $activity || ! $activity->cool) {
            return;
        }

        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;
        if ($isShepherd) {
            $isCoolMember = CoolMember::where('cool_id', $activity->cool_id)
                ->where('member_id', $memberId)
                ->where('cool_members.is_deleted', false)
                ->exists();
            if (! $isCoolMember) {
                abort(403, 'Anggota jemaat ini tidak terdaftar pada kelompok COOL Anda.');
            }
        }

        $userId = Auth::id() ?? 1;

        $attendance = Attendance::firstOrNew([
            'activity_id' => $this->activity_id,
            'member_id' => $memberId,
        ]);

        $attendance->attendance_status_id = $statusId;
        $attendance->attendance_time = now();
        $attendance->is_deleted = false;

        if (! empty($this->notes[$memberId])) {
            $attendance->note = $this->notes[$memberId];
        }

        if (! $attendance->exists) {
            $attendance->created_by = $userId;
            $attendance->date_created = now();
        } else {
            $attendance->modified_by = $userId;
            $attendance->date_modified = now();
        }

        $attendance->save();
    }

    public function saveNote(int $memberId): void
    {
        $this->authorizeActivity();

        if (! $this->activity_id) {
            return;
        }

        $noteText = $this->notes[$memberId] ?? '';
        $attendance = Attendance::where('activity_id', $this->activity_id)
            ->where('member_id', $memberId)
            ->where('is_deleted', false)
            ->first();

        if ($attendance) {
            $attendance->update([
                'note' => $noteText ?: null,
                'modified_by' => Auth::id() ?? 1,
                'date_modified' => now(),
            ]);
            session()->flash('note_saved_'.$memberId, 'Catatan tersimpan');
        }
    }

    public function markAllPresent(): void
    {
        $this->authorizeActivity();

        if (! $this->activity_id) {
            return;
        }

        $activity = Activity::find($this->activity_id);
        if (! $activity || ! $activity->cool) {
            return;
        }

        $userId = Auth::id() ?? 1;
        $presentStatus = AttendanceStatus::where('code', 'PRESENT')->first();
        $statusId = $presentStatus ? $presentStatus->attendance_status_id : 1;

        $members = Member::where('is_deleted', false)
            ->whereHas('coolMembers', function ($q) use ($activity) {
                $q->where('cool_id', $activity->cool_id)
                    ->where('cool_members.is_deleted', false);
            })
            ->get();

        foreach ($members as $member) {
            Attendance::updateOrCreate(
                [
                    'activity_id' => $this->activity_id,
                    'member_id' => $member->member_id,
                ],
                [
                    'attendance_status_id' => $statusId,
                    'attendance_time' => now(),
                    'is_deleted' => false,
                    'created_by' => $userId,
                    'date_created' => now(),
                    'modified_by' => $userId,
                    'date_modified' => now(),
                ]
            );
        }

        session()->flash('success', 'Semua anggota COOL berhasil ditandai Hadir!');
    }

    public function render()
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->orderBy('name')
            ->get();

        $activities = Activity::where('is_deleted', false)
            ->when($isShepherd, function ($q) use ($user) {
                $q->whereHas('cool', fn ($c) => $c->where('shepherd_id', $user->shepherd_id));
            })
            ->when($this->coolFilter, fn ($q) => $q->where('cool_id', $this->coolFilter))
            ->with(['cool', 'activityType'])
            ->orderByDesc('activity_date')
            ->get();

        $currentActivity = null;
        $members = collect();
        $existingAttendances = collect();
        $statuses = AttendanceStatus::where('is_deleted', false)->get();

        $stats = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'excused' => 0,
            'sick' => 0,
            'percentage' => 0,
        ];

        if ($this->activity_id) {
            $currentActivity = Activity::with(['cool', 'activityType'])->find($this->activity_id);

            if ($currentActivity && $currentActivity->cool) {
                $members = Member::where('is_deleted', false)
                    ->whereHas('coolMembers', function ($q) use ($currentActivity) {
                        $q->where('cool_id', $currentActivity->cool_id)
                            ->where('cool_members.is_deleted', false);
                    })
                    ->orderBy('name')
                    ->get();

                $attendances = Attendance::where('activity_id', $this->activity_id)
                    ->where('is_deleted', false)
                    ->with('status')
                    ->get();

                $existingAttendances = $attendances->keyBy('member_id');

                $stats['total'] = $members->count();
                $stats['present'] = $attendances->where('status.code', 'PRESENT')->count();
                $stats['absent'] = $attendances->where('status.code', 'ABSENT')->count();
                $stats['excused'] = $attendances->where('status.code', 'EXCUSED')->count();
                $stats['sick'] = $attendances->where('status.code', 'SICK')->count();
                $stats['percentage'] = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100) : 0;
            }
        }

        return view('livewire.attendances.attendance-manager', compact(
            'cools',
            'activities',
            'currentActivity',
            'members',
            'existingAttendances',
            'statuses',
            'stats',
            'isShepherd'
        ));
    }
}
