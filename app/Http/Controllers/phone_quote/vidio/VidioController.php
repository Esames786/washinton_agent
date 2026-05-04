<?php

namespace App\Http\Controllers\phone_quote\vidio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\role;
use App\AutoOrder;
use App\report;
use App\zipcodes;

use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;


use Vinkla\Hashids\Facades\Hashids;


class VidioController extends Controller
{
    public function index(){


    }
    public function uploadvidioadd(){

      return view('main.phone_quote.vidio.index');
    }



}
