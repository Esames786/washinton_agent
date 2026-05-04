<?php

namespace App\Http\Controllers;

use App\Mail\CustomMail;
use App\AutoOrder;
use App\EmailTemplate;
use App\UsedAndNewCarDealers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendCustomMailJob;
use Illuminate\Support\Facades\Validator;

class SendCustomEmail extends Controller
{
    public function sendCustomMail($id)
    {
        $emailTemplate = EmailTemplate::find($id);
    
        if (!$emailTemplate) {
            return back()->with('error', 'Email template not found!');
        }
    
        $content = $emailTemplate->title;
        $banner = $emailTemplate->banner;
    
        // $dataAutoOrder = AutoOrder::whereNotNull('oemail')
        //     ->where('oemail', 'LIKE', '%@%')
        //     ->where('oemail', 'NOT LIKE', '%test%')
        //     ->orderBy('id', 'DESC')
        //     ->distinct('oemail')
        //     // ->take(100)
        //     ->pluck('oemail');
    
        // $dataUsedAndNewCarDealers = UsedAndNewCarDealers::whereNotNull('email')
        //     ->orderBy('id', 'DESC')
        //     ->distinct('email')
        //     ->where('email', 'NOT LIKE', '%test%')
        //     // ->take(100)
        //     ->pluck('email');
    
        // $emails = $dataAutoOrder->merge($dataUsedAndNewCarDealers);
    
        // // Chunk emails into batches of 100
        // $emailChunks = $emails->chunk(100);
    
        // foreach ($emailChunks as $chunk) {
        //     try {
        //         SendCustomMailJob::dispatch($chunk, $content, $banner);
        //     } catch (\Exception $e) {
        //         continue;
        //     }
        // }
    
        return back()->with('success', 'Emails queued successfully!');
    }
}