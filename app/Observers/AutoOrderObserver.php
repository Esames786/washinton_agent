<?php

namespace App\Observers;

use App\AutoOrder;
use App\ReportNew;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AutoOrderObserver
{
    public function created(AutoOrder $autoOrder)
    {
        $duplicateOrder = AutoOrder::where('ophone', $autoOrder->ophone)
                                   ->where('id', '!=', $autoOrder->id)
                                   ->first();

        if ($duplicateOrder) {
            $autoOrder->how_did_you_find_us = null;
        } else {
            $autoOrder->how_did_you_find_us = 'none';
        }

        $autoOrder->save();
    }

    public function updating(AutoOrder $autoOrder)
    {
        $previousStatus = $autoOrder->getOriginal('pstatus');
        $previousCarrier = $autoOrder->getOriginal('carrier_id');

        $status = $autoOrder->pstatus;
        $userId = Auth::check() ? Auth::id() : 0;

        $statusFields = [
            0 => ['NEW_Created', 'NEW_User'],
            1 => ['Interested_Created', 'Interested_User'],
            2 => ['FollowMore_Created', 'FollowMore_User'],
            3 => ['AskingLow_Created', 'AskingLow_User'],
            4 => ['NotInterested_Created', 'NotInterested_User'],
            5 => ['NoResponse_Created', 'NoResponse_User'],
            6 => ['TimeQuote_Created', 'TimeQuote_User'],
            7 => ['PaymentMissing_Created', 'PaymentMissing_User'],
            8 => ['Booked_Created', 'Booked_User'],
            9 => ['Listed_Created', 'Listed_User'],
            10 => ['Schedule_Created', 'Schedule_User'],
            11 => ['Pickup_Created', 'Pickup_User'],
            12 => ['Delivered_Created', 'Delivered_User'],
            13 => ['Completed_Created', 'Completed_User'],
            14 => ['Cancel_Created', 'Cancel_User'],
            15 => ['Deleted_Created', 'Deleted_User'],
            16 => ['OwesMoney_Created', 'OwesMoney_User'],
            17 => ['CarrierUpdate_Created', 'CarrierUpdate_User'],
            18 => ['OnApproval_Created', 'OnApproval_User'],
            19 => ['CancelOnApproval_Created', 'CancelOnApproval_User'],
        ];

        // Set createdField and userField based on the current status
        if (isset($statusFields[$status])) {
            [$createdField, $userField] = $statusFields[$status];
        }

        if ($status == 10) {
            if ($previousStatus == 10 && $previousCarrier != $autoOrder->carrier_id) {
                $report = new ReportNew;
                $report->userId = $userId;
                $report->orderId = $autoOrder->id;
                $report->pstatus = $status;
        
                $existingEntries = ReportNew::where('orderId', $autoOrder->id)
                                            ->where('pstatus', $status)
                                            ->get();
        
                if ($existingEntries->count() > 0) {
                    $maxCount = $existingEntries->max('count');
                    $report->count = $maxCount + 1;
                } else {
                    $report->count = 1;
                }
        
                $report->save();
            } elseif ($previousStatus != 10) {
                $report = new ReportNew;
                $report->userId = $userId;
                $report->orderId = $autoOrder->id;
                $report->pstatus = $status;
        
                $existingEntries = ReportNew::where('orderId', $autoOrder->id)
                                            ->where('pstatus', $status)
                                            ->get();
        
                if ($existingEntries->count() > 0) {
                    $maxCount = $existingEntries->max('count');
                    $report->count = $maxCount + 1;
                } else {
                    $report->count = 1;
                }
        
                $report->save();
            }
        } else {
            if ($autoOrder->$createdField === null) {
                $autoOrder->$createdField = Carbon::now();
                $autoOrder->$userField = $userId;
        
                $report = new ReportNew;
                $report->userId = $userId;
                $report->orderId = $autoOrder->id;
                $report->pstatus = $status;
        
                $report->count = 1;
        
                $report->save();
            }
        }
    }
}
