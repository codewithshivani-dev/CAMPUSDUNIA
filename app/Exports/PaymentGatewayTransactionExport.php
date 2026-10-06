<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentGatewayTransactionExport implements FromCollection, WithHeadings
{
    protected $transactions;

    public function __construct(Collection $transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions->map(function ($t) {
            return [
                'Transaction Reference' => $t->transaction_reference,
                'Transaction Type' => $t->transaction_type ?? null,
                'User Name' => $t->user_name ?? null,
                'User ID' => $t->user_id ?? null,
                'User Type' => $t->user_type ?? null,
                'Fee Type' => $t->fee_type ?? null,
                'Payment Link ID' => $t->payment_link_id,
                'Payment Link' => $t->payment_link,
                'Gateway Payment ID' => $t->gateway_payment_id,
                'Amount (INR)' => $t->amount,
                'Currency' => $t->currency,
                'Payment Type' => $t->payment_type,
                'Payment Status' => ucfirst($t->payment_status),
                'Created At' => optional($t->created_at)->format('Y-m-d H:i:s'),
                'Updated At' => optional($t->updated_at)->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Transaction Reference',
            'Transaction Type',
            'User Name',
            'User ID',
            'User Type',
            'Fee Type',
            'Payment Link ID',
            'Payment Link',
            'Gateway Payment ID',
            'Amount (INR)',
            'Currency',
            'Payment Type',
            'Payment Status',
            'Created At',
            'Updated At',
        ];
    }
}