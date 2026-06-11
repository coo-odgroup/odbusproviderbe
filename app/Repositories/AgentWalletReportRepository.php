<?php

namespace App\Repositories;

use App\Models\AgentWallet;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class AgentWalletReportRepository
{
    protected $agentWallet;

    public function __construct(AgentWallet $agentWallet, Booking $booking)
    {
        $this->agentWallet = $agentWallet;
        $this->booking = $booking;
    }

    public function getWalletRecord(
        $user_id,
        $start_date = null,
        $end_date = null
    ) {
        $query = $this->agentWallet
            ->where('user_id', $user_id)
            ->whereNotIn('status', [2]);

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [
                $start_date . ' 00:00:00',
                $end_date . ' 23:59:59'
            ]);
        }

        return $query->orderByDesc('id');
    }

    public function selectType($data, $select_type)
    {
        if ($select_type == 'wallet_recharge') {

            $data = $data
                ->where('payment_via', '!=', '')
                ->where('transaction_type', 'c');
        } elseif ($select_type == 'pnr_booking') {

            $data = $data
                ->where('payment_via', '')
                ->where('transaction_type', 'd')
                ->whereNull('type');
        } elseif ($select_type == 'cancelled_pnr') {

            $data = $data
                ->where('type', 'Refund');
        } elseif ($select_type == 'commission') {

            $data = $data
                ->where('type', 'Commission');
        }

        return $data;
    }

    public function Pagination($data, $paginate)
    {
        $data =  $data->paginate($paginate);
        if ($data) {
            foreach ($data as $key => $v) {
                if ($v->booking_id != null) {
                    $v['pnrDetails'] = $this->booking->where('id', $v->booking_id)->get();
                }
            }
        }
        return $data;
    }

    public function Filter($data, $name)
    {
        $bookingIds = $this->booking
            ->where('pnr', 'like', '%' . $name . '%')
            ->pluck('id')
            ->toArray();

        return $data->where(function ($query) use ($name, $bookingIds) {

            $query->where('transaction_id', 'like', '%' . $name . '%')
                ->orWhere('reference_id', 'like', '%' . $name . '%')
                ->orWhere('remarks', 'like', '%' . $name . '%')
                ->orWhere('payment_via', 'like', '%' . $name . '%');

            if (!empty($bookingIds)) {
                $query->orWhereIn('booking_id', $bookingIds);
            }
        });
    }

    public function filterDate($data, $start_date, $end_date)
    {
        return $data
            ->whereBetween(
                'created_at',
                [
                    $start_date . ' 00:00:00',
                    $end_date . ' 23:59:59'
                ]
            )
            ->orderBy('created_at', 'DESC');
    }

    public function tranType($data, $tran_type)
    {
        return  $data->where('transaction_type', $tran_type);
    }
}
