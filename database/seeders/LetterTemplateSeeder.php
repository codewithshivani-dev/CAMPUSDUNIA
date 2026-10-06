<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LetterTemplateSeeder extends Seeder
{
    public function run()
    {
        // First, get all official documents with their types and IDs
        $officialDocuments = DB::table('official_documents')
            ->where('status', 'active')
            ->get()
            ->keyBy('official_document_type'); // Key by type for easy lookup

        // Map document types to their corresponding official_document_id
        $documentTypeMap = [
            'Onboarding' => 'appointment',
            'Exit' => 'termination',
            'Disciplinary' => 'warning',
            'Experience' => 'experience',
            'Payroll' => 'salary',
            'Joining' => 'offer',
            'Promotion and Performance' => 'appraisal',
            'Other' => 'complaint'
        ];

        $templates = [
            'appointment' => [
                'title' => 'Appointment',
                'style_1' => 'Dear Gaurav Singla,

We are pleased to offer you the position of Head of Department at , effective . We are confident that your skills and experience will make a valuable contribution to our organization.

Your employment will be subject to the terms and conditions of the company and its applicable policies.

Please confirm your acceptance of this offer by signing and returning a copy of this letter.

We look forward to welcoming you to the team and wish you every success in your new role.
fffffffffffffff
Yours sincerely,
rrrrrrrrrrrrr',
                'style_2' => 'To: [[employee_name]]
Subject: Appointment Letter

Dear [[employee_name]],

We are happy to confirm your appointment as [[job_title]] in the [[department]] department at [[company_name]]. Your appointment will be effective from [[joining_date]]. We are confident that your knowledge and dedication will contribute to the continued success of our organization.

Please review the terms of your employment and acknowledge your acceptance by signing this letter.

We welcome you to [[company_name]] and wish you a successful and rewarding career with us.

Sincerely,
[[company_name]]',
                'style_3' => '[[company_name]]
Human Resources Department

TO: [[employee_name]]
SUBJECT: Appointment as [[job_title]]

Dear [[employee_name]],

We are pleased to confirm your appointment as [[job_title]] at [[company_name]], effective from [[joining_date]]. We are confident that your skills and commitment will make a valuable contribution to our organization.

We look forward to your association with [[company_name]] and wish you every success in your new role.

Regards,
Human Resources
[[company_name]]',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'offer' => [
                'title' => 'Job Offer Letter',
                'style_1' => 'Dear [[employee_name]],

We are delighted to offer you the position of [[job_title]] at [[company_name]]. We believe your skills and experience will make a valuable contribution to our team. We look forward to welcoming you aboard.

Kindly confirm your acceptance of this offer at your earliest convenience.

Yours sincerely,
[[company_name]]',
                'style_2' => 'OFFER OF EMPLOYMENT

To: [[employee_name]]
Position: [[job_title]]
Company: [[company_name]]

We are pleased to offer you the role of [[job_title]] with [[company_name]]. We are excited about the opportunity to have you join our team and look forward to working with you.

Regards,
[[company_name]]',
                'style_3' => 'Subject: Employment Offer for [[job_title]]

Dear [[employee_name]],

Congratulations! We are pleased to offer you the position of [[job_title]] at [[company_name]]. We are confident that you will be a great addition to our organization and look forward to your positive response.

Best wishes,
[[company_name]]',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'termination' => [
                'title' => 'Termination Letter',
                'style_1' => 'Dear [[employee_name]],

This letter serves as formal notification that your employment with [[company_name]] has been terminated, effective immediately. We appreciate your contributions to the organization and wish you success in your future endeavors.

Sincerely,
[[company_name]]',
                'style_2' => 'TERMINATION NOTICE

TO: [[employee_name]]
FROM: [[manager_name]]
SUBJECT: Termination of Employment

This letter confirms the termination of your employment with [[company_name]]. Please complete all exit formalities and return any company property before your final working day.

Regards,
[[company_name]]',
                'style_3' => 'Subject: Notice of Termination

Dear [[employee_name]],

We regret to inform you that your employment with [[company_name]] has been terminated. Thank you for your service and contributions during your time with the company. We wish you the very best in your future endeavors.

Sincerely,
[[company_name]]',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'recommendation' => [
                'title' => 'Recommendation Letter',
                'style_1' => 'To Whom It May Concern,

I am pleased to recommend Gaurav Singla for any position or opportunity they may pursue. Throughout their time with , they consistently demonstrated professionalism, dedication, and a strong work ethic.

Sincerely,',
                'style_2' => 'RECOMMENDATION LETTER

To Whom It May Concern,

It is my pleasure to recommend [[employee_name]]. During their tenure at [[company_name]], they proved to be a reliable and committed professional. I am confident they will be an asset to any organization.

Regards,
[[company_name]]',
                'style_3' => 'Subject: Recommendation for [[employee_name]]

Dear Sir/Madam,

I am writing to recommend [[employee_name]] for future employment opportunities. Their dedication, integrity, and positive attitude made them a valued member of [[company_name]]. I wish them every success in their future career.

Sincerely,
[[company_name]]',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'complaint' => [
                'title' => 'Complaint Letter',
                'style_1' => 'Dear [Recipient],

I am writing to formally raise a complaint regarding [issue]. I request that this matter be reviewed and appropriate action be taken at the earliest convenience.

Thank you for your attention to this matter.

Sincerely,
[[employee_name]]',
                'style_2' => 'COMPLAINT LETTER

To: [Department Head]
From: [[employee_name]]
Subject: Complaint Regarding [issue]

I would like to formally report an issue concerning [issue]. I kindly request that this matter be investigated and resolved as soon as possible.

Regards,
[[employee_name]]',
                'style_3' => 'FORMAL COMPLAINT

[[employee_name]]
[[job_title]]
[[company_name]]

I am submitting this letter to report [issue]. I request that the matter be addressed promptly and that appropriate corrective action be taken.

Thank you for your consideration.',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'warning' => [
                'title' => 'Warning Letter',
                'style_1' => 'Dear [[employee_name]],

This letter serves as a formal warning regarding your conduct and/or performance. We expect immediate improvement and compliance with company policies to avoid further disciplinary action.

Sincerely,
[[company_name]]',
                'style_2' => 'WARNING NOTICE

TO: [[employee_name]]
FROM: [[manager_name]]
SUBJECT: Formal Warning

You are hereby issued a formal warning due to concerns regarding your conduct and/or work performance. Please take the necessary corrective measures to prevent any further action.

Regards,
[[company_name]]',
                'style_3' => 'Dear [[employee_name]],

This letter serves as a formal warning regarding your conduct and/or performance. We expect immediate improvement and compliance with company policies to avoid further disciplinary action.

Sincerely,
[[company_name]]
fffffffffffffffff',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'noc' => [
                'title' => 'No Objection Certificate (NOC)',
                'style_1' => 'To Whom It May Concern,

This is to certify that [[employee_name]] is an employee of [[company_name]]. We have no objection to them pursuing the stated purpose, provided it does not conflict with their employment responsibilities.

Sincerely,
[[company_name]]',
                'style_2' => 'NO OBJECTION CERTIFICATE

This is to certify that [[employee_name]] is employed with [[company_name]]. The company has no objection to their application or participation for the stated purpose.

Regards,
[[company_name]]',
                'style_3' => 'NOC

[[company_name]]

To Whom It May Concern,

This is to confirm that [[company_name]] has no objection to [[employee_name]] pursuing the stated purpose. This certificate is issued upon the employee\'s request for official use.

Authorized Signatory
[[company_name]]',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'salary' => [
                'title' => 'Salary Increment Letter',
                'style_1' => 'Dear [[employee_name]],

We are pleased to inform you that your salary has been revised to [[salary]] in recognition of your dedication and valuable contributions to [[company_name]]. We appreciate your continued commitment and wish you continued success.

Sincerely,
[[company_name]]',
                'style_2' => 'SALARY INCREMENT NOTICE

TO: [[employee_name]]
FROM: [[manager_name]]
SUBJECT: Salary Revision

We are pleased to notify you that your salary has been increased to [[salary]]. This revision reflects your performance and contribution to the organization.

Regards,
[[company_name]]',
                'style_3' => 'Subject: Salary Increment

Dear [[employee_name]],

Congratulations! We are pleased to inform you that your salary has been revised to [[salary]]. This increment is in appreciation of your hard work and commitment to [[company_name]]. We wish you continued success in your role.

Sincerely,
[[company_name]]',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'experience' => [
                'title' => 'Experience Certificate',
                'style_1' => 'To Whom It May Concern,

This is to certify that [[employee_name]] was employed with [[company_name]] as a [[job_title]]. During their tenure, they performed their responsibilities with dedication and professionalism. We wish them every success in their future endeavors.

Sincerely,
[[company_name]]',
                'style_2' => 'EXPERIENCE CERTIFICATE

To Whom It May Concern,

This is to certify that [[employee_name]] worked with [[company_name]] as [[job_title]]. Their conduct and performance throughout their employment were satisfactory, and we appreciate their contributions to the organization.

Regards,
[[company_name]]',
                'style_3' => 'Certificate of Experience

To Whom It May Concern,

This is to certify that [[employee_name]] served as [[job_title]] at [[company_name]]. During their employment, they demonstrated commitment, professionalism, and a positive attitude. We thank them for their service and wish them continued success in their future career.

Authorized Signatory
[[company_name]]',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ],
            'appraisal' => [
                'title' => 'Appraisal Letter',
                'style_1' => 'Dear Gaurav Singla,

We are pleased to share the results of your performance appraisal. Your dedication and contributions to  are sincerely appreciated, and we encourage you to continue your excellent work.

Congratulations and best wishes.

Sincerely,',
                'style_2' => 'PERFORMANCE APPRAISAL

Employee: [[employee_name]]
Company: [[company_name]]

Your performance has been reviewed and recognized. We appreciate your commitment, professionalism, and valuable contributions to the organization. Keep up the excellent work.

Regards,
[[company_name]]',
                'style_3' => 'Subject: Performance Appraisal

Dear [[employee_name]],

Congratulations on your performance and achievements at [[company_name]]. Your hard work and dedication are highly valued, and we look forward to your continued success with the organization.

Sincerely,
[[company_name]]',
                'style_labels' => ['Style 1', 'Style 2', 'Style 3']
            ]
        ];

        foreach ($templates as $key => $t) {
            // Find the official_document_id for this template type
            $officialDocumentType = array_search($key, $documentTypeMap);
            $officialDoc = $officialDocuments->get($officialDocumentType);

            // Skip if no matching official document found
            if (!$officialDoc) {
                $this->command->warn("No official document found for type: {$officialDocumentType}");
                continue;
            }

            // Get the institute_id from the official document
            $instituteId = $officialDoc->institute_id;
            $officialDocId = $officialDoc->official_documenttype_id;

            // Generate a random letter ID
            $letterId = 'LTR-' . strtoupper(Str::random(4));

            $payload = [
                'title' => $t['title'],
                'institute_id' => $instituteId,
                'official_documenttype_id' => $officialDocId,
                'key' => $key,
                'letter_id' => $letterId,
            ];

            // Handle styles based on schema
            if (Schema::hasColumn('letter_templates', 'styles')) {
                $payload['styles'] = json_encode([
                    $t['style_1'] ?? '',
                    $t['style_2'] ?? '',
                    $t['style_3'] ?? ''
                ], JSON_UNESCAPED_UNICODE);
            }

            // Handle style_1, style_2, style_3 for older schema
            if (Schema::hasColumn('letter_templates', 'style_1')) {
                $payload['style_1'] = $t['style_1'] ?? '';
                $payload['style_2'] = $t['style_2'] ?? '';
                $payload['style_3'] = $t['style_3'] ?? '';
            }

            // Handle style_labels
            if (isset($t['style_labels']) && Schema::hasColumn('letter_templates', 'style_labels')) {
                $payload['style_labels'] = json_encode($t['style_labels'], JSON_UNESCAPED_UNICODE);
            } elseif (Schema::hasColumn('letter_templates', 'style_labels')) {
                $payload['style_labels'] = json_encode(['Style 1', 'Style 2', 'Style 3'], JSON_UNESCAPED_UNICODE);
            }

            $now = Carbon::now();
            $existing = DB::table('letter_templates')->where('key', $key)->first();

            if ($existing) {
                $payload['updated_at'] = $now;
                DB::table('letter_templates')->where('key', $key)->update($payload);
                $this->command->info("Updated template: {$key}");
            } else {
                $payload['created_at'] = $now;
                $payload['updated_at'] = $now;
                DB::table('letter_templates')->insert($payload);
                $this->command->info("Created template: {$key}");
            }
        }
    }
}