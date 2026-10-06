<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamStructureOfflineExam;
use Carbon\Carbon;

class UpdateExamStatuses extends Command
{
    protected $signature = 'exams:update-status';
    protected $description = 'Automatically update exam statuses based on date and time';

    public function handle()
    {
        $now = Carbon::now();
        $today = Carbon::today();
        
        // Get all published exams
        $exams = ExamStructureOfflineExam::where('is_published', true)
            ->whereIn('status', ['draft', 'scheduled', 'ongoing'])
            ->get();
        
        foreach ($exams as $exam) {

    $examDate = Carbon::parse($exam->exam_date)->startOfDay();
    $examDateOnly = $examDate->toDateString();

    $startTime = Carbon::parse(
        $examDateOnly . ' ' . Carbon::parse($exam->start_time)->toTimeString()
    );

    $endTime = Carbon::parse(
        $examDateOnly . ' ' . Carbon::parse($exam->end_time)->toTimeString()
    );
    dump([
    'now' => $now->toDateTimeString(),
    'start' => $startTime->toDateTimeString(),
    'end' => $endTime->toDateTimeString(),
]);


   if ($examDate->equalTo($today)) {

    if ($now->gte($startTime) && $now->lt($endTime)) {
        $exam->update(['status' => 'ongoing']);

    } elseif ($now->gte($endTime)) {
        $exam->update(['status' => 'completed']);

    } else {
        $exam->update(['status' => 'scheduled']);
    }
}
        }
        $this->info('Exam statuses updated successfully.');
    }
}