<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmitCard;
use App\Models\ExamName;
use App\Models\ExamStructureOfflineExam;
use App\Models\AddBlock;
use App\Models\AddBuilding;
use App\Models\AddFloor;
use App\Models\AddRooms;
use App\Models\InstituteBasicDetails;
use App\Models\ProductDetails;
use App\Models\StudentParentDetails;
use App\Traits\InstituteBranchAccess;
use App\Traits\SectionNameHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AdmitCardController extends Controller
{
    use InstituteBranchAccess, SectionNameHelper;

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $context = $this->context();

        $records = $this
            ->publishedExamQuery($context)
            ->get();

        $exams = $records
            ->groupBy(
                fn ($exam) => $this->cohortKey($exam)
            )
            ->map(
                fn (Collection $subjects) =>
                    $this->cohortSummary(
                        $subjects,
                        $context
                    )
            )
            ->filter(function ($exam) use ($request) {

                $haystack =
                    $exam->exam_name . ' ' .
                    $exam->department_name . ' ' .
                    $exam->course_name;

                return
                    (
                        !$request->filled('exam_name_id')
                        ||
                        $exam->exam_name_id ===
                            $request->exam_name_id
                    )
                    &&
                    (
                        !$request->filled('academic_year')
                        ||
                        $exam->academic_year ===
                            $request->academic_year
                    )
                    &&
                    (
                        !$request->filled('search')
                        ||
                        stripos(
                            $haystack,
                            $request->search
                        ) !== false
                    );
            })
            ->sortByDesc(
                fn ($exam) =>
                    $exam->academic_year .
                    '|' .
                    (
                        $exam->first_exam_date
                        ?: ''
                    )
            )
            ->values();

        $examNames = ExamName::where(
            'institute_id',
            $context['institute_id']
        )
            ->whereIn(
                'exam_name_id',
                $records
                    ->pluck('exam_name_id')
                    ->unique()
            )
            ->orderByDesc('academic_year')
            ->orderBy('name')
            ->get();

        $academicYears = $records
            ->pluck('academic_year')
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        return view(
            'instituteAdmin.AdmitCards.index',
            compact(
                'exams',
                'examNames',
                'academicYears'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Side
    |--------------------------------------------------------------------------
    */

    public function studentIndex()
{
    $student = StudentParentDetails::where(
        'user_id',
        auth()->id()
    )->firstOrFail();

    $admitCards = AdmitCard::with([
        'examName'
    ])
        ->where(
            'institute_id',
            $student->institute_id
        )
        ->where(
            'student_hash_id',
            $student->student_hash_id
        )

        // IMPORTANT:
        // Student should see generated as well as published cards.
        ->whereIn('status', [
            'generated',
            'published',
            'printed',
            'downloaded',
        ])

        // PDF must actually exist.
        ->whereNotNull('pdf_path')

        // Branch restriction
        ->where(function ($query) use ($student) {
            $query
                ->where(
                    'branch_id',
                    $student->branch_id
                )
                ->orWhereNull(
                    'branch_id'
                );
        })

        // Only show admit cards for valid/published exams.
        ->whereExists(function ($query) use ($student) {
            $query
                ->select(DB::raw(1))
                ->from(
                    'exam_structure_offline_exams as exams'
                )
                ->whereColumn(
                    'exams.exam_name_id',
                    'admit_cards.exam_name_id'
                )
                ->whereColumn(
                    'exams.academic_year',
                    'admit_cards.academic_year'
                )
                ->where(
                    'exams.institute_id',
                    $student->institute_id
                )
                ->where(
                    'exams.is_published',
                    true
                )
                ->where(
                    'exams.status',
                    '!=',
                    'cancelled'
                )
                ->whereDate(
                    'exams.exam_date',
                    '>=',
                    now()->toDateString()
                );
        })

        // Newest generated/published card first
        ->orderByRaw(
            'COALESCE(published_at, generated_at) DESC'
        )
        ->get();

        return view(
            'instituteAdmin.StudentFiles.AdmitCards',
            compact('admitCards')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    public function students(
        Request $request,
        string $examNameId,
        string $academicYear
    ) {
        $context = $this->context();

        $cohort = $this->cohortFromRequest(
            $request,
            $examNameId,
            $academicYear,
            $context
        );

        if (!$cohort) {
            return redirect()
                ->route('admit-cards.index')
                ->with(
                    'error',
                    'This published examination cohort no longer exists.'
                );
        }

        $students = $this->eligibleStudents(
            $cohort['exams'],
            $request,
            $context,
            $academicYear
        );

        $cards = $this
            ->cardQuery(
                $context,
                $cohort['attributes'],
                $examNameId,
                $academicYear
            )
            ->get()
            ->keyBy('student_hash_id');

        $students->each(
            function ($student) use (
                $cards,
                $cohort
            ) {

                $student->admitCard =
                    $cards->get(
                        $student->student_hash_id
                    );

                $student->eligibleExamCount =
                    $cohort['exams']->count();

                $student->subjectNames =
                    $cohort['exams']
                        ->map(
                            fn ($exam) =>
                                $exam->subject->subject_name
                                ?? $exam->subject_id
                        )
                        ->filter()
                        ->unique()
                        ->implode(', ');
            }
        );

        $totalStudents =
            $students->count();

        $generatedCount =
            $students
                ->filter(
                    fn ($student) =>
                        in_array(
                            optional(
                                $student->admitCard
                            )->status,
                            [
                                'generated',
                                'published',
                                'printed',
                                'downloaded',
                            ],
                            true
                        )
                )
                ->count();

        $publishedCount =
            $students
                ->filter(
                    fn ($student) =>
                        optional(
                            $student->admitCard
                        )->status === 'published'
                )
                ->count();

        $pendingCount =
            $students
                ->filter(
                    fn ($student) =>
                        !$student->admitCard
                )
                ->count();

        $allGenerated =
            $totalStudents > 0
            &&
            $generatedCount === $totalStudents;

        if ($request->filled('status')) {

            $students =
                $students
                    ->filter(
                        fn ($student) =>
                            $request->status ===
                            'not_generated'
                                ? !$student->admitCard
                                : optional(
                                    $student->admitCard
                                )->status ===
                                    $request->status
                    )
                    ->values();
        }

        $exam =
            $cohort['summary'];

        return view(
            'instituteAdmin.AdmitCards.students',
            [
                'examRecords' =>
                    $cohort['exams'],

                'selectedExams' =>
                    $cohort['exams'],

                'exam' =>
                    $exam,

                'examTitle' =>
                    $exam->exam_name,

                'examNameId' =>
                    $examNameId,

                'academicYear' =>
                    $academicYear,

                'cohort' =>
                    $cohort['attributes'],

                'students' =>
                    $students,

                'totalStudents' =>
                    $totalStudents,

                'generatedCount' =>
                    $generatedCount,

                'publishedCount' =>
                    $publishedCount,

                'pendingCount' =>
                    $pendingCount,

                'allGenerated' =>
                    $allGenerated,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Single
    |--------------------------------------------------------------------------
    */

    public function generate(
        Request $request,
        string $studentHashId
    ) {
        // try {

            $location =
                $this->validatedGenerationLocation(
                    $request
                );

            $context =
                $this->context();

            $cohort =
                $this->validatedCohort(
                    $request,
                    $context
                );

            $student =
                $this->eligibleStudent(
                    $studentHashId,
                    $cohort['exams'],
                    $request,
                    $context
                );

            if (!$student) {
                return $this
                    ->manageStudentsRedirect(
                        $request
                    )
                    ->with(
                        'error',
                        'This student is not eligible for the selected examination cohort.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Create / Reset Admit Card
            |--------------------------------------------------------------------------
            */

            $card =
                $this->markGenerated(
                    $context,
                    $cohort['attributes'],
                    $request->exam_name_id,
                    $request->academic_year,
                    $studentHashId,
                    $location
                );

            /*
            |--------------------------------------------------------------------------
            | Generate fresh PDF
            |--------------------------------------------------------------------------
            */

            $this->storePdf(
                $card,
                $student,
                $cohort['exams']
            );

            return $this
                ->manageStudentsRedirect(
                    $request
                )
                ->with(
                    'success',
                    'Admit card generated successfully.'
                );

        // } catch (Throwable $exception) {

        //     Log::error(
        //         'Admit card generation failed.',
        //         [
        //             'student_hash_id' =>
        //                 $studentHashId,

        //             'exam_name_id' =>
        //                 $request->input(
        //                     'exam_name_id'
        //                 ),

        //             'academic_year' =>
        //                 $request->input(
        //                     'academic_year'
        //                 ),

        //             'exception' =>
        //                 $exception->getMessage(),

        //             'file' =>
        //                 $exception->getFile(),

        //             'line' =>
        //                 $exception->getLine(),
        //         ]
        //     );

        //     return $this
        //         ->manageStudentsRedirect(
        //             $request
        //         )
        //         ->with(
        //             'error',
        //             'Unable to generate admit card: ' .
        //             $exception->getMessage()
        //         );
        // }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate All
    |--------------------------------------------------------------------------
    */

    public function generateAll(
        Request $request
    ) {
        try {

            $location =
                $this->validatedGenerationLocation(
                    $request
                );

            $context =
                $this->context();

            $cohort =
                $this->validatedCohort(
                    $request,
                    $context
                );

            $students =
                $this->eligibleStudents(
                    $cohort['exams'],
                    $request,
                    $context,
                    $request->academic_year
                );

            $generated = 0;

            foreach ($students as $student) {

                $card =
                    $this->markGenerated(
                        $context,
                        $cohort['attributes'],
                        $request->exam_name_id,
                        $request->academic_year,
                        $student->student_hash_id,
                        $location
                    );

                $this->storePdf(
                    $card,
                    $student,
                    $cohort['exams']
                );

                $generated++;
            }

            return $this
                ->manageStudentsRedirect(
                    $request
                )
                ->with(
                    'success',
                    $generated .
                    ' admit card(s) generated successfully.'
                );

        } catch (Throwable $exception) {

            Log::error(
                'Bulk admit card generation failed.',
                [
                    'exception' =>
                        $exception->getMessage(),

                    'file' =>
                        $exception->getFile(),

                    'line' =>
                        $exception->getLine(),
                ]
            );

            return $this
                ->manageStudentsRedirect(
                    $request
                )
                ->with(
                    'error',
                    'Unable to generate admit cards: ' .
                    $exception->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Publish Single
    |--------------------------------------------------------------------------
    */

    public function publish(
        Request $request,
        string $studentHashId
    ) {
        $context =
            $this->context();

        $cohort =
            $this->validatedCohort(
                $request,
                $context
            );

        $card =
            $this->getAdmitCard(
                $context,
                $cohort['attributes'],
                $request->exam_name_id,
                $request->academic_year,
                $studentHashId
            );

        if (!$card) {
            return back()->with(
                'error',
                'Generate the admit card before publishing it.'
            );
        }

        if (
            !in_array(
                $card->status,
                [
                    'generated',
                    'printed',
                    'downloaded',
                ],
                true
            )
        ) {
            return back()->with(
                'error',
                'Only generated admit cards can be published.'
            );
        }

        $card->update([
            'status' =>
                'published',

            'published_at' =>
                now(),
        ]);

        return back()->with(
            'success',
            'Admit card published successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Publish All
    |--------------------------------------------------------------------------
    */

    public function publishAll(
        Request $request
    ) {
        $context =
            $this->context();

        $cohort =
            $this->validatedCohort(
                $request,
                $context
            );

        $studentIds =
            $this
                ->eligibleStudents(
                    $cohort['exams'],
                    $request,
                    $context,
                    $request->academic_year
                )
                ->pluck(
                    'student_hash_id'
                );

        $count =
            $this
                ->cardQuery(
                    $context,
                    $cohort['attributes'],
                    $request->exam_name_id,
                    $request->academic_year
                )
                ->whereIn(
                    'student_hash_id',
                    $studentIds
                )
                ->whereIn(
                    'status',
                    [
                        'generated',
                        'printed',
                        'downloaded',
                    ]
                )
                ->update([
                    'status' =>
                        'published',

                    'published_at' =>
                        now(),
                ]);

        return back()->with(
            $count
                ? 'success'
                : 'error',
            $count
                ? $count .
                    ' admit card(s) published successfully.'
                : 'No generated admit cards are available to publish.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Preview
    |--------------------------------------------------------------------------
    */

    public function preview(
        Request $request,
        string $studentHashId
    ) {
        [
            $student,
            $exams,
            $card
        ] =
            $this->admitCardData(
                $request,
                $studentHashId
            );

        if (!$card) {
            return back()->with(
                'error',
                'Generate the admit card first.'
            );
        }

        return view(
            'instituteAdmin.AdmitCards.preview',
            $this->viewData(
                $student,
                $exams,
                $card
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Download Single
    |--------------------------------------------------------------------------
    */

    public function download(
        Request $request,
        string $studentHashId
    ) {
        try {

            [
                $student,
                $exams,
                $card
            ] = $this->admitCardData(
                $request,
                $studentHashId
            );

            if (!$card) {
                return back()->with(
                    'error',
                    'Generate the admit card first.'
                );
            }

            /*
             * Always rebuild the PDF through the single PDF pipeline.
             * This guarantees that Download uses the same current
             * template/CSS as Generate and Print.
             */
            $path = $this->storePdf(
                $card,
                $student,
                $exams
            );

            $card->update([
                'downloaded_at' => now(),
            ]);

            $filePath = Storage::disk('public')->path($path);

            if (!is_file($filePath)) {
                throw new \RuntimeException(
                    'Generated admit card PDF could not be found.'
                );
            }

            return response()->download(
                $filePath,
                'admit-card-' .
                (
                    $student->registration_number
                    ?: $studentHashId
                ) .
                '.pdf',
                [
                    'Content-Type' => 'application/pdf',
                ]
            );

        } catch (Throwable $exception) {

            Log::error(
                'Admit card download failed.',
                [
                    'student_hash_id' => $studentHashId,
                    'exception' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            );

            return back()->with(
                'error',
                'Unable to download admit card: ' .
                $exception->getMessage()
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT
    |--------------------------------------------------------------------------
    |
    | This generates a completely fresh PDF from database records.
    |
    */

    public function print(
        Request $request,
        string $studentHashId
    ) {
        try {

            /*
            |--------------------------------------------------------------------------
            | Get student + exam + admit card
            |--------------------------------------------------------------------------
            */
            [
                $student,
                $exams,
                $card
            ] = $this->admitCardData(
                $request,
                $studentHashId
            );

            /*
            |--------------------------------------------------------------------------
            | Admit card must exist
            |--------------------------------------------------------------------------
            */
            if (!$card) {

                return back()->with(
                    'error',
                    'Generate the admit card first.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate status
            |--------------------------------------------------------------------------
            */
            if (!in_array(
                $card->status,
                [
                    'generated',
                    'published',
                    'printed',
                    'downloaded',
                ],
                true
            )) {

                return back()->with(
                    'error',
                    'Generate the admit card before printing it.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | PDF path must exist
            |--------------------------------------------------------------------------
            */
            if (empty($card->pdf_path)) {

                return back()->with(
                    'error',
                    'Admit card PDF is not available. Please generate the card again.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Check stored PDF
            |--------------------------------------------------------------------------
            */
            $disk = Storage::disk('public');

            if (!$disk->exists($card->pdf_path)) {

                return back()->with(
                    'error',
                    'Admit card PDF file could not be found. Please generate the card again.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Mark as printed
            |--------------------------------------------------------------------------
            */
            $card->update([
                'printed_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Get exact existing PDF
            |--------------------------------------------------------------------------
            */
            $filePath = $disk->path(
                $card->pdf_path
            );

            /*
            |--------------------------------------------------------------------------
            | File name
            |--------------------------------------------------------------------------
            */
            $filename =
                'admit-card-' .
                (
                    $student->registration_number
                    ?: $studentHashId
                ) .
                '.pdf';

            /*
            |--------------------------------------------------------------------------
            | Open existing PDF in browser
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | We are NOT calling storePdf().
            |
            | Therefore the PDF that was generated earlier is
            | exactly the PDF being printed.
            |
            |--------------------------------------------------------------------------
            */
            return response()->file(
                $filePath,
                [
                    'Content-Type' => 'application/pdf',

                    'Content-Disposition' =>
                        'inline; filename="' .
                        $filename .
                        '"',

                    'Cache-Control' =>
                        'no-store, no-cache, must-revalidate',

                    'Pragma' => 'no-cache',

                    'Expires' => '0',
                ]
            );

        } catch (Throwable $exception) {

            Log::error(
                'Admit card print failed.',
                [
                    'student_hash_id' =>
                        $studentHashId,

                    'exam_name_id' =>
                        $request->input(
                            'exam_name_id'
                        ),

                    'academic_year' =>
                        $request->input(
                            'academic_year'
                        ),

                    'exception' =>
                        $exception->getMessage(),

                    'file' =>
                        $exception->getFile(),

                    'line' =>
                        $exception->getLine(),
                ]
            );

            return back()->with(
                'error',
                'Unable to print admit card. Please try again.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Download All
    |--------------------------------------------------------------------------
    */

    public function downloadAll(
        Request $request
    ) {
        try {

            $context =
                $this->context();

            $cohort =
                $this->validatedCohort(
                    $request,
                    $context
                );

            $students =
                $this->eligibleStudents(
                    $cohort['exams'],
                    $request,
                    $context,
                    $request->academic_year
                );

            if ($students->isEmpty()) {
                return back()->with(
                    'error',
                    'No eligible students found.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Fresh exams + instructions
            |--------------------------------------------------------------------------
            */

            $exams =
                $this->freshExamRecords(
                    $cohort['exams']
                );

            $cards =
                $students->map(
                    function ($student) use (
                        $context,
                        $cohort,
                        $request,
                        $exams
                    ) {

                        $card =
                            $this->getAdmitCard(
                                $context,
                                $cohort['attributes'],
                                $request->exam_name_id,
                                $request->academic_year,
                                $student->student_hash_id
                            );

                        if (!$card) {

                            $card =
                                $this->markGenerated(
                                    $context,
                                    $cohort['attributes'],
                                    $request->exam_name_id,
                                    $request->academic_year,
                                    $student->student_hash_id,
                                    [
                                        'location_mode' =>
                                            'inside',

                                        'outside_building' =>
                                            null,

                                        'outside_block' =>
                                            null,

                                        'outside_floor' =>
                                            null,

                                        'outside_room' =>
                                            null,

                                        'outside_address' =>
                                            null,

                                        'outside_city' =>
                                            null,

                                        'outside_state' =>
                                            null,

                                        'outside_pincode' =>
                                            null,
                                    ]
                                );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Store fresh individual PDF
                        |--------------------------------------------------------------------------
                        */

                        $this->storePdf(
                            $card,
                            $student,
                            $exams
                        );

                        return $this->viewData(
                            $student,
                            $exams,
                            $card
                        );
                    }
                );

            return Pdf::loadView(
                'instituteAdmin.AdmitCards.bulk-pdf',
                [
                    'cards' =>
                        $cards,
                ]
            )
                ->setPaper(
                    'a4',
                    'portrait'
                )
                ->download(
                    'admit-cards-' .
                    $request->academic_year .
                    '.pdf'
                );

        } catch (Throwable $exception) {

            Log::error(
                'Bulk admit card download failed.',
                [
                    'exception' =>
                        $exception->getMessage(),

                    'file' =>
                        $exception->getFile(),

                    'line' =>
                        $exception->getLine(),
                ]
            );

            return back()->with(
                'error',
                'Unable to download admit cards: ' .
                $exception->getMessage()
            );
        }
    }

 

    /*
    |--------------------------------------------------------------------------
    | Context
    |--------------------------------------------------------------------------
    */

    private function context(): array
    {
        $context =
            $this->getInstituteBranchContext();

        abort_unless(
            $context['institute_id'],
            403
        );

        return $context;
    }

    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    private function manageStudentsRedirect(
        Request $request
    ) {
        $routeParams = [
            'examNameId' =>
                $request->input(
                    'exam_name_id'
                ),

            'academicYear' =>
                $request->input(
                    'academic_year'
                ),
        ];

        $query =
            $request->only([
                'exam_name_id',
                'academic_year',
                'department_id',
                'course_id',
                'subtype_id',
                'semester_id',
                'section_id',
                'search',
                'status',
            ]);

        return redirect()->route(
            'admit-cards.students',
            array_merge(
                $routeParams,
                $query
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Published Exam Query
    |--------------------------------------------------------------------------
    */

    private function publishedExamQuery(
        array $context
    ) {
        return ExamStructureOfflineExam::with([
            'department',
            'course',
            'subject',
            'examNameDetail',
            'instructions' => function ($query) {

                $query
                    ->orderBy(
                        'sort_order',
                        'asc'
                    )
                    ->orderBy(
                        'id',
                        'asc'
                    );
            },
        ])
            ->where(
                'institute_id',
                $context['institute_id']
            )
            ->when(
                $context['is_branch_admin'],
                fn ($q) =>
                    $q->where(
                        'branch_id',
                        $context['branch_id']
                    ),
                fn ($q) =>
                    $q->whereNull(
                        'branch_id'
                    )
            )
            ->where(
                'is_published',
                true
            )
            ->where(
                'status',
                '!=',
                'cancelled'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Cohort Key
    |--------------------------------------------------------------------------
    */

    private function cohortKey(
        $exam
    ): string {
        return implode(
            '|',
            [
                $exam->exam_name_id,

                $exam->academic_year,

                $exam->department_id,

                $exam->course_id,

                $exam->subtype_id,

                $this->semesterValue(
                    $exam->semester_id
                ),

                implode(
                    ',',
                    $this->tokens(
                        $exam->section_id
                    )
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cohort Summary
    |--------------------------------------------------------------------------
    */

    private function cohortSummary(
        Collection $subjects,
        array $context
    ): object {

        $first =
            $subjects->first();

        $attributes =
            $this->cohortAttributes(
                $first
            );

        $cards =
            $this->cardQuery(
                $context,
                $attributes,
                $first->exam_name_id,
                $first->academic_year
            );

        $course =
            $first->course
            ?:
            ProductDetails::where(
                'product_id',
                $first->subtype_id
            )->first();

        $subjectNames =
            $subjects
                ->map(
                    fn ($exam) =>
                        optional(
                            $exam->subject
                        )->subject_name
                        ?:
                        $exam->subject_id
                )
                ->filter()
                ->unique()
                ->values();

        return (object) array_merge(
            $attributes,
            [
                'exam_name_id' =>
                    $first->exam_name_id,

                'academic_year' =>
                    $first->academic_year,

                'exam_name' =>
                    optional(
                        $first->examNameDetail
                    )->name
                    ??
                    $first->exam_name,

                'department_name' =>
                    optional(
                        $first->department
                    )->department
                    ??
                    $first->department_id,

                'course_name' =>
                    optional($course)
                        ->course_type
                    ?:
                    (
                        $first->course_id
                        ?: '—'
                    ),

                'subtype_name' =>
                    optional($course)
                        ->sub_type
                    ?:
                    (
                        $first->subtype_id
                        ?: '—'
                    ),

                'subject_names' =>
                    $subjectNames,

                'subject_count' =>
                    $subjects->count(),

                'subjects' =>
                    $subjects,

                'first_exam_date' =>
                    $subjects->min(
                        'exam_date'
                    ),

                'last_exam_date' =>
                    $subjects->max(
                        'exam_date'
                    ),

                'section_label' =>
                    $this->sectionNamesForExam(
                        $first
                    ),

                'generated_count' =>
                    (clone $cards)
                        ->whereIn(
                            'status',
                            [
                                'generated',
                                'published',
                                'printed',
                                'downloaded',
                            ]
                        )
                        ->count(),

                'published_count' =>
                    (clone $cards)
                        ->where(
                            'status',
                            'published'
                        )
                        ->count(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cohort Attributes
    |--------------------------------------------------------------------------
    */

    private function cohortAttributes(
        $exam
    ): array {
        return [
            'department_id' =>
                $exam->department_id,

            'course_id' =>
                $exam->course_id,

            'subtype_id' =>
                $exam->subtype_id,

            'semester_id' =>
                $this->semesterValue(
                    $exam->semester_id
                ),

            'section_id' =>
                implode(
                    ',',
                    $this->tokens(
                        $exam->section_id
                    )
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Cohort From Request
    |--------------------------------------------------------------------------
    */

    private function cohortFromRequest(
        Request $request,
        string $examNameId,
        string $academicYear,
        array $context
    ): ?array {

        $query =
            $this
                ->publishedExamQuery(
                    $context
                )
                ->where(
                    'exam_name_id',
                    $examNameId
                )
                ->where(
                    'academic_year',
                    $academicYear
                );

        foreach (
            [
                'department_id',
                'course_id',
                'subtype_id',
            ] as $field
        ) {

            $value =
                $request->input(
                    $field
                );

            $query =
                $value === null ||
                $value === ''
                    ? $query->whereNull(
                        $field
                    )
                    : $query->where(
                        $field,
                        $value
                    );
        }

        $semester =
            $this->semesterValue(
                $request->input(
                    'semester_id'
                )
            );

        if (
            $semester ===
            'all_semesters'
        ) {

            $query->where(
                function ($q) {

                    $q
                        ->whereNull(
                            'semester_id'
                        )
                        ->orWhere(
                            'semester_id',
                            ''
                        )
                        ->orWhere(
                            'semester_id',
                            'all_semesters'
                        );
                }
            );

        } else {

            $query->where(
                'semester_id',
                $semester
            );
        }

        $section =
            implode(
                ',',
                $this->tokens(
                    $request->input(
                        'section_id'
                    )
                )
            );

        $records =
            $query
                ->get()
                ->filter(
                    fn ($exam) =>
                        implode(
                            ',',
                            $this->tokens(
                                $exam->section_id
                            )
                        ) ===
                        $section
                )
                ->values();

        if ($records->isEmpty()) {
            return null;
        }

        return [
            'exams' =>
                $records,

            'attributes' =>
                $this->cohortAttributes(
                    $records->first()
                ),

            'summary' =>
                $this->cohortSummary(
                    $records,
                    $context
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validated Cohort
    |--------------------------------------------------------------------------
    */

    private function validatedCohort(
        Request $request,
        array $context
    ): array {

        $request->validate([
            'exam_name_id' =>
                'required|string',

            'academic_year' =>
                'required|string',

            'department_id' =>
                'required',

            'section_id' =>
                'required',
        ]);

        $cohort =
            $this->cohortFromRequest(
                $request,
                $request->exam_name_id,
                $request->academic_year,
                $context
            );

        if ($cohort === null) {

            abort(
                404,
                'Published examination cohort not found.'
            );
        }

        return $cohort;
    }

    /*
    |--------------------------------------------------------------------------
    | Eligible Students
    |--------------------------------------------------------------------------
    */

    private function eligibleStudents(
        Collection $exams,
        Request $request,
        array $context,
        string $academicYear
    ): Collection {

        if ($exams->isEmpty()) {
            return collect();
        }

        $query =
            StudentParentDetails::with([
                'academicTransportDetails' =>
                    function ($q) use (
                        $context,
                        $academicYear
                    ) {

                        $q
                            ->where(
                                'institute_id',
                                $context['institute_id']
                            )
                            ->where(
                                'academic_year',
                                $academicYear
                            )
                            ->when(
                                $context['is_branch_admin'],
                                fn ($q) =>
                                    $q->where(
                                        'branch_id',
                                        $context['branch_id']
                                    ),
                                fn ($q) =>
                                    $q->whereNull(
                                        'branch_id'
                                    )
                            );
                    },

                'rollNumber',

                'documents',
            ])
                ->where(
                    'institute_id',
                    $context['institute_id']
                )
                ->whereHas(
                    'academicTransportDetails',
                    function ($q) use (
                        $context,
                        $academicYear
                    ) {

                        $q
                            ->where(
                                'institute_id',
                                $context['institute_id']
                            )
                            ->where(
                                'academic_year',
                                $academicYear
                            )
                            ->when(
                                $context['is_branch_admin'],
                                fn ($q) =>
                                    $q->where(
                                        'branch_id',
                                        $context['branch_id']
                                    ),
                                fn ($q) =>
                                    $q->whereNull(
                                        'branch_id'
                                    )
                            );
                    }
                );

        if ($request->filled('search')) {

            $search =
                trim(
                    $request->search
                );

            $query->where(
                function ($q) use (
                    $search
                ) {

                    $q
                        ->where(
                            'registration_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'first_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'middle_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'last_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas(
                            'rollNumber',
                            fn ($q) =>
                                $q->where(
                                    'roll_number',
                                    'like',
                                    "%{$search}%"
                                )
                        );
                }
            );
        }

        return $query
            ->get()
            ->filter(
                fn ($student) =>
                    $this
                        ->examsForStudent(
                            $exams,
                            $student
                                ->academicTransportDetails
                        )
                        ->isNotEmpty()
            )
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Eligible Single Student
    |--------------------------------------------------------------------------
    */

    private function eligibleStudent(
        string $studentHashId,
        Collection $exams,
        Request $request,
        array $context
    ) {

        $students =
            $this->eligibleStudents(
                $exams,
                new Request(),
                $context,
                $request->academic_year
            );

        return $students->first(
            fn ($student) =>
                $student->student_hash_id ===
                $studentHashId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Exams For Student
    |--------------------------------------------------------------------------
    */

    private function examsForStudent(
        Collection $exams,
        $academic
    ): Collection {

        if (!$academic) {
            return collect();
        }

        $sections =
            $this->tokens(
                $academic->section_id
            );

        return $exams
            ->filter(
                function ($exam) use (
                    $academic,
                    $sections
                ) {

                    $course =
                        $this->matchesAcademicValue(
                            $exam->course_id,
                            $academic->course_type_id,
                            $academic->course_type
                        );

                    $subtype =
                        $this->matchesAcademicValue(
                            $exam->subtype_id,
                            $academic->course_subtype_id,
                            $academic->course_subtype
                        );

                    $semester =
                        $this->semesterValue(
                            $exam->semester_id
                        ) ===
                        'all_semesters'
                        ||
                        (
                            (string)
                                $exam->semester_id
                            ===
                            (string)
                                (
                                    $academic
                                        ->semester_id
                                    ?? ''
                                )
                        );

                    $section =
                    in_array(
                        'all',
                        $this->tokens(
                            $exam->section_id
                        ),
                        true
                    )
                    ||
                    (
                        (bool) array_intersect(
                            $this->tokens(
                                $exam->section_id
                            ),
                            $sections
                        )
                    );

                    return
                        (string)
                            $exam->department_id
                        ===
                        (string)
                            (
                                $academic
                                    ->department_id
                                ?? ''
                            )
                        &&
                        $course
                        &&
                        $subtype
                        &&
                        $semester
                        &&
                        $section;
                }
            )
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Match Academic Value
    |--------------------------------------------------------------------------
    */

    private function matchesAcademicValue(
        $examValue,
        $academicId,
        $academicName
    ): bool {

        if (
            $examValue === null
            ||
            $examValue === ''
        ) {
            return true;
        }

        foreach (
            [
                $academicId,
                $academicName,
            ] as $academicValue
        ) {

            if (
                $academicValue !== null
                &&
                $academicValue !== ''
                &&
                strcasecmp(
                    trim(
                        (string)
                            $examValue
                    ),
                    trim(
                        (string)
                            $academicValue
                    )
                ) === 0
            ) {
                return true;
            }
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Admit Card Query
    |--------------------------------------------------------------------------
    */

    private function cardQuery(
        array $context,
        array $attributes,
        string $examNameId,
        string $academicYear
    ) {

        $query =
            AdmitCard::where(
                'institute_id',
                $context['institute_id']
            )
                ->when(
                    $context['is_branch_admin'],
                    fn ($q) =>
                        $q->where(
                            'branch_id',
                            $context['branch_id']
                        ),
                    fn ($q) =>
                        $q->whereNull(
                            'branch_id'
                        )
                )
                ->where(
                    'exam_name_id',
                    $examNameId
                )
                ->where(
                    'academic_year',
                    $academicYear
                );

        foreach (
            $attributes as $field => $value
        ) {

            if (
                $field ===
                    'semester_id'
                &&
                $value ===
                    'all_semesters'
            ) {

                $query->where(
                    function ($q) {

                        $q
                            ->whereNull(
                                'semester_id'
                            )
                            ->orWhere(
                                'semester_id',
                                ''
                            )
                            ->orWhere(
                                'semester_id',
                                'all_semesters'
                            );
                    }
                );

            } else {

                $query =
                    $value === null ||
                    $value === ''
                        ? $query->whereNull(
                            $field
                        )
                        : $query->where(
                            $field,
                            $value
                        );
            }
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Get Admit Card
    |--------------------------------------------------------------------------
    */

    private function getAdmitCard(
        array $context,
        array $attributes,
        string $examNameId,
        string $academicYear,
        string $studentHashId
    ): ?AdmitCard {

        return $this
            ->cardQuery(
                $context,
                $attributes,
                $examNameId,
                $academicYear
            )
            ->where(
                'student_hash_id',
                $studentHashId
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Mark Generated
    |--------------------------------------------------------------------------
    */

    private function markGenerated(
        array $context,
        array $attributes,
        string $examNameId,
        string $academicYear,
        string $studentHashId,
        array $location
    ): AdmitCard {

        $key =
            $this->admitCardKey(
                $context,
                $attributes,
                $examNameId,
                $academicYear,
                $studentHashId
            );

        $identity = array_merge(
            [
                'institute_id' =>
                    $context['institute_id'],

                'branch_id' =>
                    $context['is_branch_admin']
                        ? $context['branch_id']
                        : null,

                'exam_name_id' =>
                    $examNameId,

                'academic_year' =>
                    $academicYear,

                'student_hash_id' =>
                    $studentHashId,
            ],
            $attributes
        );

        if (
            Schema::hasColumn(
                'admit_cards',
                'admit_card_key'
            )
        ) {
            $identity[
                'admit_card_key'
            ] = $key;
        }

        return AdmitCard::updateOrCreate(
            $identity,
            array_merge(
                $location,
                [
                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT:
                    | Generation does NOT publish.
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                        'generated',

                    'generated_at' =>
                        now(),

                    'published_at' =>
                        null,

                    /*
                    |--------------------------------------------------------------------------
                    | Clear old print/download timestamps
                    |--------------------------------------------------------------------------
                    */

                    'printed_at' =>
                        null,

                    'downloaded_at' =>
                        null,
                ]
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Generation Location
    |--------------------------------------------------------------------------
    */

    private function validatedGenerationLocation(
        Request $request
    ): array {

        $data =
            $request->validate([
                'location_mode' =>
                    'required|in:inside,outside',

                'outside_building' =>
                    'nullable|string|max:255',

                'outside_block' =>
                    'nullable|string|max:255',

                'outside_floor' =>
                    'nullable|string|max:255',

                'outside_room' =>
                    'nullable|string|max:255',

                'outside_address' =>
                    'nullable|string|max:1000',

                'outside_city' =>
                    'nullable|string|max:255',

                'outside_state' =>
                    'nullable|string|max:255',

                'outside_pincode' =>
                    'nullable|digits:6',
            ]);

        return [
            'location_mode' =>
                $data['location_mode'],

            'outside_building' =>
                $data['location_mode'] ===
                'outside'
                    ? (
                        $data['outside_building']
                        ?? null
                    )
                    : null,

            'outside_block' =>
                $data['location_mode'] ===
                'outside'
                    ? (
                        $data['outside_block']
                        ?? null
                    )
                    : null,

            'outside_floor' =>
                $data['location_mode'] ===
                'outside'
                    ? (
                        $data['outside_floor']
                        ?? null
                    )
                    : null,

            'outside_room' =>
                $data['location_mode'] ===
                'outside'
                    ? (
                        $data['outside_room']
                        ?? null
                    )
                    : null,

            'outside_address' =>
                $data['location_mode'] ===
                'outside'
                    ? ($data['outside_address'] ?? null)
                    : null,

            'outside_city' =>
                $data['location_mode'] ===
                'outside'
                    ? ($data['outside_city'] ?? null)
                    : null,

            'outside_state' =>
                $data['location_mode'] ===
                'outside'
                    ? ($data['outside_state'] ?? null)
                    : null,

            'outside_pincode' =>
                $data['location_mode'] ===
                'outside'
                    ? ($data['outside_pincode'] ?? null)
                    : null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Fresh Exam Records
    |--------------------------------------------------------------------------
    |
    | THIS IS THE MAIN FIX FOR DYNAMIC INSTRUCTIONS.
    |--------------------------------------------------------------------------
    */

    private function freshExamRecords(
        Collection $exams
    ): Collection {

        $examIds =
            $exams
                ->pluck('id')
                ->filter()
                ->values();

        if ($examIds->isEmpty()) {
            return collect();
        }

        $freshExams =
            ExamStructureOfflineExam::with([
                'department',
                'course',
                'subject',
                'examNameDetail',

                'instructions' =>
                    function ($query) {

                        $query
                            ->orderBy(
                                'sort_order',
                                'asc'
                            )
                            ->orderBy(
                                'id',
                                'asc'
                            );
                    },
            ])
                ->whereIn(
                    'id',
                    $examIds
                )
                ->orderBy(
                    'exam_date',
                    'asc'
                )
                ->orderBy(
                    'start_time',
                    'asc'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Preserve original cohort order
        |--------------------------------------------------------------------------
        */

        $order =
            $examIds->flip();

        return $freshExams
            ->sortBy(
                function ($exam) use (
                    $order
                ) {

                    return $order->get(
                        $exam->id,
                        PHP_INT_MAX
                    );
                }
            )
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Store PDF
    |--------------------------------------------------------------------------
    */

    private function storePdf(
        AdmitCard $card,
        $student,
        Collection $exams
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Fresh exam data
        |--------------------------------------------------------------------------
        */
        $freshExams = $this->freshExamRecords($exams);

        /*
        |--------------------------------------------------------------------------
        | Stable PDF path
        |--------------------------------------------------------------------------
        */
        $path =
            'admit-cards/' .
            $card->admit_card_key .
            '.pdf';

        /*
        |--------------------------------------------------------------------------
        | Shared view data
        |--------------------------------------------------------------------------
        */
        $viewData = $this->viewData(
            $student,
            $freshExams,
            $card
        );

        /*
        |--------------------------------------------------------------------------
        | Single PDF rendering pipeline
        |--------------------------------------------------------------------------
        |
        | pdf.blade.php owns the A4 @page definition.
        | _document.blade.php owns the actual admit-card design.
        |
        | The same generated file is used by Download and Print.
        |--------------------------------------------------------------------------
        */
        $pdf = Pdf::loadView(
            'instituteAdmin.AdmitCards.pdf',
            $viewData
        )->setPaper(
            'a4',
            'portrait'
        );

        $pdfOutput = $pdf->output();

        if (empty($pdfOutput)) {
            throw new \RuntimeException(
                'DomPDF returned an empty PDF.'
            );
        }

        Storage::disk('public')->put(
            $path,
            $pdfOutput
        );

        $card->update([
            'pdf_path' => $path,
        ]);

        return $path;
    }

    /*
    |--------------------------------------------------------------------------
    | Admit Card Key
    |--------------------------------------------------------------------------
    */

    private function admitCardKey(
        array $context,
        array $attributes,
        string $examNameId,
        string $academicYear,
        string $studentHashId
    ): string {

        return hash(
            'sha256',
            implode(
                '|',
                [
                    $context['institute_id'],

                    $context['is_branch_admin']
                        ? $context['branch_id']
                        : '',

                    $examNameId,

                    $academicYear,

                    $attributes[
                        'department_id'
                    ] ?? '',

                    $attributes[
                        'course_id'
                    ] ?? '',

                    $attributes[
                        'subtype_id'
                    ] ?? '',

                    $attributes[
                        'semester_id'
                    ] ?? '',

                    $attributes[
                        'section_id'
                    ] ?? '',

                    $studentHashId,
                ]
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Admit Card Data
    |--------------------------------------------------------------------------
    */

    private function admitCardData(
        Request $request,
        string $studentHashId
    ): array {

        $context =
            $this->context();

        $cohort =
            $this->validatedCohort(
                $request,
                $context
            );

        /*
        |--------------------------------------------------------------------------
        | Fresh exam records
        |--------------------------------------------------------------------------
        */

        $exams =
            $this->freshExamRecords(
                $cohort['exams']
            );

        $student =
            $this->eligibleStudent(
                $studentHashId,
                $exams,
                $request,
                $context
            );

        abort_unless(
            $student,
            403,
            'This student is not eligible for the selected examination cohort.'
        );

        return [
            $student,

            $exams,

            $this->getAdmitCard(
                $context,
                $cohort['attributes'],
                $request->exam_name_id,
                $request->academic_year,
                $studentHashId
            ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | View Data
    |--------------------------------------------------------------------------
    |
    | This method is used by:
    |
    | - Preview
    | - Print
    | - Download
    | - Generate
    | - Bulk PDF
    |
    |--------------------------------------------------------------------------
    */


    /**
     * =========================================================================
     * View Data
     * =========================================================================
     *
     * Used by:
     * - Preview
     * - Print
     * - Download
     * - Generate
     * - Bulk PDF
     *
     * IMPORTANT:
     * Instructions are loaded directly from the exam_instructions table
     * directly from the exam_instructions table using exam_structure_offline_exam_id.
     */
    private function viewData(
        $student,
        Collection $exams,
        ?AdmitCard $card
    ): array {

        /*
        |--------------------------------------------------------------------------
        | 1. Reload fresh exam records
        |--------------------------------------------------------------------------
        |
        | This is important because instructions may have been added/updated
        | after the original cohort was loaded.
        |
        */

        $exams = $this->freshExamRecords(
            $exams
        );

        /*
        |--------------------------------------------------------------------------
        | 2. Make sure exams is always a Collection
        |--------------------------------------------------------------------------
        */

        if (!$exams instanceof Collection) {
            $exams = collect($exams ?? []);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Dynamic Instructions
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Instructions are stored in the exam_instructions table.
        |
        | exam_instructions.exam_structure_offline_exam_id
        |     -> exam_structure_offline_exams.id
        |
        | exam_instructions.instruction
        |     -> actual instruction text
        |
        | exam_instructions.sort_order
        |     -> display order
        |
        | Do NOT read instructions from exam_structure_offline_exams.
        |--------------------------------------------------------------------------
        */

        $examIds = $exams
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();

        $instructionTexts = [];

        if ($examIds->isNotEmpty()) {
            $instructionTexts = DB::table('exam_instructions')
                ->whereIn(
                    'exam_structure_offline_exam_id',
                    $examIds->toArray()
                )
                ->whereNotNull('instruction')
                ->where('instruction', '!=', '')
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->pluck('instruction')
                ->map(function ($instruction) {
                    return trim((string) $instruction);
                })
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        }

        Log::info(
            'Admit card dynamic instructions loaded from exam_instructions',
            [
                'student_hash_id' => $student->student_hash_id ?? null,
                'exam_ids' => $examIds->toArray(),
                'instruction_count' => count($instructionTexts),
                'instructions' => $instructionTexts,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 4. Building
        |--------------------------------------------------------------------------
        */

        $buildingIds = $exams
            ->pluck('building_id')
            ->filter()
            ->unique()
            ->values();

        $buildings = AddBuilding::whereIn(
            'id',
            $buildingIds
        )
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | 5. Block
        |--------------------------------------------------------------------------
        */

        $blockIds = $exams
            ->pluck('block_id')
            ->filter()
            ->unique()
            ->values();

        $blocks = AddBlock::whereIn(
            'id',
            $blockIds
        )
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | 6. Floor
        |--------------------------------------------------------------------------
        */

        $floorIds = $exams
            ->pluck('floor_id')
            ->filter()
            ->unique()
            ->values();

        $floors = AddFloor::whereIn(
            'id',
            $floorIds
        )
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | 7. Room
        |--------------------------------------------------------------------------
        */

        $roomIds = $exams
            ->pluck('room_id')
            ->filter()
            ->unique()
            ->values();

        $rooms = AddRooms::whereIn(
            'id',
            $roomIds
        )
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | 8. Attach location details to each exam
        |--------------------------------------------------------------------------
        */

        $exams->each(
            function ($exam) use (
                $buildings,
                $blocks,
                $floors,
                $rooms
            ) {

                $exam->building_detail =
                    $buildings->get(
                        $exam->building_id
                    );

                $exam->block_detail =
                    $blocks->get(
                        $exam->block_id
                    );

                $exam->floor_detail =
                    $floors->get(
                        $exam->floor_id
                    );

                $exam->room_detail =
                    $rooms->get(
                        $exam->room_id
                    );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | 9. Student Photo
        |--------------------------------------------------------------------------
        */

        $photo = optional(
            $student->documents
        )->student_photo;

        $photoData = null;

        if ($photo) {

            $photoPath = ltrim(
                $photo,
                '/'
            );

            /*
            | Prevent duplicated storage/app/public path
            */

            if (str_starts_with(
                $photoPath,
                'storage/app/public/'
            )) {

                $photoPath = substr(
                    $photoPath,
                    strlen('storage/app/public/')
                );
            }

            if (str_starts_with(
                $photoPath,
                'public/'
            )) {

                $photoPath = substr(
                    $photoPath,
                    strlen('public/')
                );
            }

            $path = storage_path(
                'app/public/' .
                $photoPath
            );

            if (is_file($path)) {

                $mimeType =
                    mime_content_type($path)
                    ?: 'image/jpeg';

                $fileContents =
                    file_get_contents($path);

                if ($fileContents !== false) {

                    $photoData =
                        'data:' .
                        $mimeType .
                        ';base64,' .
                        base64_encode(
                            $fileContents
                        );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 10. Academic Details
        |--------------------------------------------------------------------------
        */

        $academic =
            $student->academicTransportDetails;

        /*
        |--------------------------------------------------------------------------
        | 11. Section Name
        |--------------------------------------------------------------------------
        */

        $sectionName = null;

        if (
            $academic &&
            $exams->isNotEmpty()
        ) {

            $sectionName =
                collect(
                    $this->tokens(
                        $academic->section_id
                    )
                )
                    ->map(
                        function ($id) use (
                            $exams,
                            $student
                        ) {

                            $firstExam =
                                $exams->first();

                            if (!$firstExam) {
                                return null;
                            }

                            return
                                $this->getSectionDisplayName(
                                    $id,
                                    $firstExam->subtype_id,
                                    $student->institute_id,
                                    $firstExam->branch_id
                                );
                        }
                    )
                    ->filter()
                    ->unique()
                    ->implode(', ');
        }

        /*
        |--------------------------------------------------------------------------
        | 12. Institute
        |--------------------------------------------------------------------------
        */

        $institute =
            InstituteBasicDetails::where(
                'fincap_merchant_id',
                $student->institute_id
            )->first();

        /*
        |--------------------------------------------------------------------------
        | 13. Dynamic instruction log
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Admit card PDF instructions loaded',
            [
                'student_hash_id' => $student->student_hash_id ?? null,
                'exam_ids' => $exams->pluck('id')->values()->toArray(),
                'instruction_count' => count($instructionTexts),
                'instructions' => $instructionTexts,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 14. Return View Data
        |--------------------------------------------------------------------------
        */

        return [

            /*
            | Student
            */

            'student' =>
                $student,

            /*
            | Student Name
            */

            'studentName' =>
                $this->studentName(
                    $student
                ),

            /*
            | Academic Details
            */

            'academic' =>
                $academic,

            /*
            | Section
            */

            'sectionName' =>
                $sectionName,

            /*
            | Exam Records
            */

            'exams' =>
                $exams,

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Dynamic Instructions
            |--------------------------------------------------------------------------
            |
            | _document.blade.php must use this variable.
            |
            */

            'instructionTexts' => $instructionTexts,
            
            /*
            | Admit Card
            */

            'card' =>
                $card,

            /*
            | Student Photo
            */

            'photoData' =>
                $photoData,

            /*
            | Institute
            */

            'institute' =>
                $institute,
        ];
    }



    /*
    |--------------------------------------------------------------------------
    | Student Name
    |--------------------------------------------------------------------------
    */

    private function studentName(
        $student
    ): string {

        return trim(
            implode(
                ' ',
                array_filter(
                    [
                        $student->first_name
                            ?? '',

                        $student->middle_name
                            ?? '',

                        $student->last_name
                            ?? '',
                    ]
                )
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Tokens
    |--------------------------------------------------------------------------
    */

    private function tokens(
        $value
    ): array {

        $tokens =
            array_values(
                array_filter(
                    array_map(
                        fn ($token) =>
                            strtolower(
                                trim(
                                    $token
                                )
                            ),
                        explode(
                            ',',
                            (string)
                                $value
                        )
                    )
                )
            );

        sort(
            $tokens,
            SORT_NATURAL
        );

        return $tokens;
    }

    /*
    |--------------------------------------------------------------------------
    | Semester
    |--------------------------------------------------------------------------
    */

    private function semesterValue(
        $value
    ): ?string {

        return
            $value === null
            ||
            $value === ''
            ||
            $value ===
                'all_semesters'
                ? 'all_semesters'
                : (string) $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Section Names
    |--------------------------------------------------------------------------
    */

    private function sectionNamesForExam(
        $exam
    ): string {

        $sectionIds =
            $this->tokens(
                $exam->section_id
            );

        if (empty($sectionIds)) {
            return 'All Sections';
        }

        return collect(
            $sectionIds
        )
            ->map(
                fn ($id) =>
                    $this->getSectionDisplayName(
                        $id,
                        $exam->subtype_id,
                        $exam->institute_id,
                        $exam->branch_id
                    )
            )
            ->implode(', ');
    }

    public function studentView(int $admitCardId)
{
    $student = StudentParentDetails::where(
        'user_id',
        auth()->id()
    )->firstOrFail();

    $card = AdmitCard::whereKey(
        $admitCardId
    )
        ->where(
            'student_hash_id',
            $student->student_hash_id
        )
        ->where(
            'institute_id',
            $student->institute_id
        )
        ->whereIn('status', [
            'generated',
            'published',
            'printed',
            'downloaded',
        ])
        ->whereNotNull(
            'pdf_path'
        )
        ->firstOrFail();

    /*
    |--------------------------------------------------------------------------
    | Check PDF exists
    |--------------------------------------------------------------------------
    */
    abort_unless(
        Storage::disk('public')->exists(
            $card->pdf_path
        ),
        404,
        'Admit card PDF not found.'
    );

    /*
    |--------------------------------------------------------------------------
    | Open PDF in browser
    |--------------------------------------------------------------------------
    */
    return response()->file(
        Storage::disk('public')->path(
            $card->pdf_path
        ),
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]
    );
    }

        /**
     * Student Print Admit Card
     *
     * Uses the already generated PDF.
     */
    public function studentPrint(int $admitCardId)
    {
        $student = StudentParentDetails::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Find student's admit card
        |--------------------------------------------------------------------------
        */
        $card = AdmitCard::whereKey(
            $admitCardId
        )
            ->where(
                'student_hash_id',
                $student->student_hash_id
            )
            ->where(
                'institute_id',
                $student->institute_id
            )
            ->whereIn(
                'status',
                [
                    'generated',
                    'published',
                    'printed',
                    'downloaded',
                ]
            )
            ->whereNotNull(
                'pdf_path'
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Check PDF exists
        |--------------------------------------------------------------------------
        */
        $disk = Storage::disk('public');

        abort_unless(
            $disk->exists($card->pdf_path),
            404,
            'Admit card PDF not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Mark printed
        |--------------------------------------------------------------------------
        */
        $card->update([
            'printed_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | File path
        |--------------------------------------------------------------------------
        */
        $filePath = $disk->path(
            $card->pdf_path
        );

        /*
        |--------------------------------------------------------------------------
        | Filename
        |--------------------------------------------------------------------------
        */
        $filename =
            'admit-card-' .
            (
                $student->registration_number
                ?: $student->student_hash_id
            ) .
            '.pdf';

        /*
        |--------------------------------------------------------------------------
        | Open existing PDF
        |--------------------------------------------------------------------------
        */
        return response()->file(
            $filePath,
            [
                'Content-Type' => 'application/pdf',

                'Content-Disposition' =>
                    'inline; filename="' .
                    $filename .
                    '"',

                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate',

                'Pragma' => 'no-cache',

                'Expires' => '0',
            ]
        );
    }

    public function studentDownload(
        Request $request,
        int $admitCardId
    ) {
        $student = StudentParentDetails::with([
            'academicTransportDetails',
            'documents',
            'rollNumber',
        ])
            ->where(
                'user_id',
                auth()->id()
            )
            ->firstOrFail();

        $card = AdmitCard::whereKey(
            $admitCardId
        )
            ->where(
                'student_hash_id',
                $student->student_hash_id
            )
            ->where(
                'institute_id',
                $student->institute_id
            )

            // Allow student to access generated cards also.
            ->whereIn('status', [
                'generated',
                'published',
                'printed',
                'downloaded',
            ])

            ->whereNotNull(
                'pdf_path'
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Security check
        |--------------------------------------------------------------------------
        | Make sure the PDF actually exists on the public storage disk.
        */
        abort_unless(
            Storage::disk('public')->exists(
                $card->pdf_path
            ),
            404,
            'Admit card PDF not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Mark as downloaded
        |--------------------------------------------------------------------------
        */
        $card->update([
            'downloaded_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Download PDF
        |--------------------------------------------------------------------------
        */
        return response()->download(
            Storage::disk('public')->path(
                $card->pdf_path
            ),
            'admit-card-' .
            (
                $student->registration_number
                ?: $student->student_hash_id
            ) .
            '.pdf'
        );
    }
}

