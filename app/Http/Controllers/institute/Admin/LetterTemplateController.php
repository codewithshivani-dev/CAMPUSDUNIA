<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\LetterTemplate;

class LetterTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = LetterTemplate::all();
        if ($templates->count() === 0) {
            // fallback defaults (trimmed versions)
            $defaults = [
                'appointment' => [
                    'title' => 'Appointment Letter',
                    'styles' => [
                        "Dear [[employee_name]],\n\nWe are delighted to offer you the position of [[job_title]] at [[company_name]], effective [[joining_date]].\n\nYours sincerely,\n[[company_name]]",
                        "To: [[employee_name]]\nDate: [[joining_date]]\nSubject: Appointment as [[job_title]]\n\nDear [[employee_name]],\n\nIt is our pleasure to appoint you as [[job_title]] in the [[department]] department at [[company_name]].",
                        "[[company_name]]\nHuman Resources\n\nTO: [[employee_name]]\nSUBJECT: Appointment – [[job_title]]\n\nThis letter confirms your appointment as [[job_title]] with [[company_name]]."
                    ]
                ],
                'offer' => [
                    'title' => 'Job Offer Letter',
                    'styles' => [
                        "Dear [[employee_name]],\n\nWe are pleased to extend an offer of employment for the position of [[job_title]] at [[company_name]].",
                        "OFFER LETTER\n\n[[employee_name]]\n[[job_title]] (proposed)\n[[company_name]]",
                        "Subject: Job Offer – [[job_title]]\n\nDear [[employee_name]],\n\nAfter a thorough selection process, we are happy to offer you the position."
                    ]
                ],
                'termination' => [
                    'title' => 'Termination Letter',
                    'styles' => [
                        "Dear [[employee_name]],\n\nThis letter serves as formal notification that your employment with [[company_name]] is terminated effective [Date].",
                        "TERMINATION NOTICE\n\nTO: [[employee_name]]\nFROM: [[manager_name]]\nSUBJECT: Termination of employment",
                        "Subject: Termination – [[employee_name]]\n\nDear [[employee_name]],\n\nWe regret to inform you that your employment with [[company_name]] is being terminated."
                    ]
                ],
                'recommendation' => [
                    'title' => 'Recommendation Letter',
                    'styles' => [
                        "To Whom It May Concern,\n\nI am pleased to recommend [[employee_name]] for any position or program they may pursue.",
                        "RECOMMENDATION LETTER\n\nTo Whom It May Concern,\n\nI strongly recommend [[employee_name]]...",
                        "Subject: Recommendation for [[employee_name]]\n\nDear Sir/Madam,\n\nI am writing to highly recommend [[employee_name]]..."
                    ]
                ],
                'complaint' => [
                    'title' => 'Complaint Letter',
                    'styles' => [
                        "Dear [Recipient],\n\nI am writing to formally lodge a complaint regarding [issue] that occurred on [Date].",
                        "COMPLAINT LETTER\n\nTo: [Department Head]\nFrom: [[employee_name]]\nSubject: Complaint about [issue]",
                        "Formal Complaint\n\n[[employee_name]]\n[[job_title]]\n[[company_name]]"
                    ]
                ],
                'warning' => [
                    'title' => 'Warning Letter',
                    'styles' => [
                        "Dear [[employee_name]],\n\nThis is a formal warning regarding your conduct/performance on [Date(s)].",
                        "WARNING NOTICE\n\nTO: [[employee_name]]\nFROM: [[manager_name]]\nSUBJECT: Formal warning",
                        "Subject: Warning – [[employee_name]]\n\nThis is your final opportunity to correct your behavior."
                    ]
                ],
                'noc' => [
                    'title' => 'No Objection Certificate (NOC)',
                    'styles' => [
                        "To Whom It May Concern,\n\nThis is to certify that [[employee_name]]...",
                        "NO OBJECTION CERTIFICATE\n\nThis certifies that [[employee_name]] is a bona fide employee of [[company_name]]...",
                        "NOC\n\n[[company_name]]\n[Date]\n\nTo Whom It May Concern,"
                    ]
                ],
                'salary' => [
                    'title' => 'Salary Increment Letter',
                    'styles' => [
                        "Dear [[employee_name]],\n\nWe are pleased to announce a revision in your compensation...",
                        "SALARY INCREMENT\n\nTO: [[employee_name]]\nFROM: [[manager_name]]\nSUBJECT: Salary revision",
                        "Subject: Increment Letter\n\nDear [[employee_name]],\n\nIn recognition of your dedication..."
                    ]
                ],
                'experience' => [
                    'title' => 'Experience Certificate',
                    'styles' => [
                        "To Whom It May Concern,\n\nThis is to certify that [[employee_name]] was employed with [[company_name]] from [Start Date] to [End Date].",
                        "EXPERIENCE CERTIFICATE\n\nThis certifies that [[employee_name]] worked as [[job_title]] at [[company_name]]...",
                        "Certificate of Experience\n\nTo Whom It May Concern,\n\n[[employee_name]] served as [[job_title]]..."
                    ]
                ]
            ];

            return response()->json($defaults);
        }

        // transform DB records into same structure the frontend expects
        $out = [];
        foreach ($templates as $t) {
            $documentType = $t->document_type ?? null;
            $letterId = $t->letter_id ?? null;

            if (Schema::hasColumn('letter_templates', 'official_documenttype_id')) {
                $officialDocument = null;
                if (!empty($t->official_documenttype_id)) {
                    $officialDocument = DB::table('official_documents')
                        ->where('official_documenttype_id', $t->official_documenttype_id)
                        ->first();
                }

                if ($officialDocument && !empty($officialDocument->official_document_type)) {
                    $documentType = $officialDocument->official_document_type;
                }
            }

            $out[$t->key] = [
                'id' => $t->id,
                'title' => $t->title,
                'styles' => $t->styles ?: [],
                'style_labels' => $t->style_labels,
                'document_type' => $documentType ?? 'others',
                'letter_id' => $letterId,
                'template_1' => $t->style_1 ?? null,
                'template_2' => $t->style_2 ?? null,
                'template_3' => $t->style_3 ?? null
            ];
        }

        return response()->json($out);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:letter_templates,key',
            'title' => 'required|string',
            'styles' => 'required|array|min:1',
            'document_type' => 'sometimes|nullable|string'
        ]);

        $tpl = new LetterTemplate();
        $tpl->key = $data['key'];
        $tpl->title = $data['title'];
        if (array_key_exists('document_type', $data)) {
            $tpl->document_type = $data['document_type'];
        }

        if (Schema::hasColumn('letter_templates', 'styles')) {
            $tpl->styles = $data['styles'];
        } else {
            $tpl->style_1 = $data['styles'][0] ?? null;
            $tpl->style_2 = $data['styles'][1] ?? null;
            $tpl->style_3 = $data['styles'][2] ?? null;
        }

        if (Schema::hasColumn('letter_templates', 'style_labels')) {
            $tpl->style_labels = array_map(function ($idx) {
                return 'Template '.($idx + 1);
            }, array_keys($data['styles']));
        }

        $tpl->save();

        return response()->json($tpl, 201);
    }

    public function update(Request $request, $id)
    {
        $tpl = LetterTemplate::findOrFail($id);
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'styles' => 'sometimes|required|array|min:1',
            'document_type' => 'sometimes|nullable|string'
        ]);

        if (Schema::hasColumn('letter_templates', 'styles')) {
            $tpl->styles = $data['styles'];
        } else {
            // fallback to legacy style_1/2/3 columns when styles JSON column is not present
            $tpl->style_1 = $data['styles'][0] ?? null;
            $tpl->style_2 = $data['styles'][1] ?? null;
            $tpl->style_3 = $data['styles'][2] ?? null;
        }

        if (isset($data['title'])) {
            $tpl->title = $data['title'];
        }

        if (array_key_exists('document_type', $data)) {
            $tpl->document_type = $data['document_type'];
        }

        $tpl->save();

        return response()->json($tpl);
    }

    public function destroy($id)
    {
        $tpl = LetterTemplate::findOrFail($id);
        $tpl->delete();
        return response()->json(['deleted' => true]);
    }
}
