<?php

namespace App\Http\Controllers\phone_quote\admin_reports;

use App\call_history;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\role;
use App\AutoOrder;
use App\report;
use App\zipcodes;
use App\count_click;
use App\carrier;

use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use App\count_day;
use Carbon;
use Vinkla\Hashids\Facades\Hashids;

class AdminReportController extends Controller
{

    public function sales_report(Request $request)
    {
        if (Auth::check()) {

            $user = Auth::user();
//          $data = AutoOrder::where('pstatus', '=', 13)
//              ->orderBy('id')
//              ->paginate(50);

            $data = DB::table('order as aorder')
                ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                ->where('opayment.payment_status', '=', 'Paid')
                ->paginate(50);

            $sumsale = DB::table('order as aorder')
                ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                ->where('opayment.payment_status', '=', 'Paid')
                ->sum('aorder.payment');
            $sumlistedprice = DB::table('order as aorder')
                ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                ->where('opayment.payment_status', '=', 'Paid')
                ->sum('aorder.listed_price');
            $sumpaycarrier = DB::table('order as aorder')
                ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                ->where('opayment.payment_status', '=', 'Paid')
                ->sum('aorder.pay_carrier');
            $sumcod = DB::table('order as aorder')
                ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                ->where('opayment.payment_status', '=', 'Paid')
                ->sum('aorder.cod_cop');
            $sumbalance = DB::table('order as aorder')
                ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                ->where('opayment.payment_status', '=', 'Paid')
                ->sum('aorder.balance');

            $sumprofit = DB::table('profit')
                 ->sum('profit');


            $datee = date('Y-m');
            $display = 'no';
    
            if (isset($request->pass)) {
                $check = \App\ReportPassword::first();
                if (!empty($check)) {
                    if(Hash::check($request->pass,$check->password))
                    {
                        $display = 'yes';
                    }
                     else {
                        $display = 'no';
                    }
                } else {
                    $display = 'no';
                }
            }

            return view('main.phone_quote.admin_reports.sales_report', compact('data', 'sumsale', 'datee', 'sumlistedprice', 'sumpaycarrier', 'sumcod', 'sumbalance','sumprofit','display'));

        } else {
            return redirect('/loginn/');
        }

    }



    public function fetch_sales_data2(Request $request)
    {
        if (Auth::check()) {

            $req = 0;
            $monthv = "";
            $yearv = "";
            $display = "no";
            
            $user = Auth::user();
            
            if (!empty($request->datev)) {
                $display = "yes";
                $salesdate = $request->datev;
                $monthv = date('m', strtotime($request->datev));
                $yearv = date('Y', strtotime($request->datev));


                $data = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->whereYear('opayment.created_at', '=', $yearv)
                    ->whereMonth('opayment.created_at', '=', $monthv)
                    ->paginate(50);
                $sumsale = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->whereYear('opayment.created_at', '=', $yearv)
                    ->whereMonth('opayment.created_at', '=', $monthv)
                    ->sum('payment');


                $sumlistedprice = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->whereYear('opayment.created_at', '=', $yearv)
                    ->whereMonth('opayment.created_at', '=', $monthv)
                    ->sum('aorder.listed_price');
                $sumpaycarrier = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->whereYear('opayment.created_at', '=', $yearv)
                    ->whereMonth('opayment.created_at', '=', $monthv)
                    ->sum('aorder.pay_carrier');
                $sumcod = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->whereYear('opayment.created_at', '=', $yearv)
                    ->whereMonth('opayment.created_at', '=', $monthv)
                    ->sum('aorder.cod_cop');
                $sumbalance = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->whereYear('opayment.created_at', '=', $yearv)
                    ->whereMonth('opayment.created_at', '=', $monthv)
                    ->sum('aorder.balance');

                  $sumprofit = DB::table('profit')
                    ->whereYear('created_at', '=', $yearv)
                    ->whereMonth('created_at', '=', $monthv)
                    ->sum('profit');

                $datee = date('Y-m');


            } else {
                $display = "yes";
                $data = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->paginate(50);

                $sumsale = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->sum('payment');

                $sumlistedprice = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->sum('aorder.listed_price');
                $sumpaycarrier = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->sum('aorder.pay_carrier');
                $sumcod = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->sum('aorder.cod_cop');
                $sumbalance = DB::table('order as aorder')
                    ->leftjoin('orderpayments as opayment', 'aorder.id', '=', 'opayment.orderId')
                    ->where('opayment.payment_status', '=', 'Paid')
                    ->sum('aorder.balance');

                $sumprofit = DB::table('profit')
                       ->sum('profit');

                $datee = date('Y-m');

            }
            return view('main.phone_quote.admin_reports.sales_report_load', compact('data', 'sumsale', 'datee', 'sumlistedprice', 'sumpaycarrier', 'sumcod', 'sumbalance','display','sumprofit'))->render();

        } else {
            return redirect('/loginn/');
        }
    }
}
