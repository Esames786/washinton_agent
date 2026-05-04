<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReviewWebsiteLink;
use App\OrderWebsiteEmail;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Mail\SendLinkEmail;
use App\OrderFeedback;

class WebsiteLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $link = ReviewWebsiteLink::where('name','LIKE','%'.$request->search.'%')->orderBy('created_at','DESC')->paginate(10);
        if($request->ajax())
        {
            return view('main.website-link.search',compact('link'));
        }
        return view('main.website-link.index',compact('link'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'website_name'=>'required|min:6|max:255|unique:review_website_links,name,'.$request->id,
            'website_link'=>'required|min:6|max:255|unique:review_website_links,link,'.$request->id,
            'status'=>'required'
        ]);
        
        if($validator->passes())
        {
            $link = ReviewWebsiteLink::find($request->id);
            if(!isset($link->id))
            {
                $link = new ReviewWebsiteLink;
            }
            $link->name = $request->website_name;
            $link->link = $request->website_link;
            $link->status = $request->status;
            $link->save();
            
            return response()->json([
                'message'=>'Save',
                'status'=>true,
                'status_code'=>200
            ]);
        }
        else
        {
            return response()->json([
                'error'=>$validator->errors(),
                'status'=>false,
                'status_code'=>400
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function linkClicked(Request $request)
    {
        $link = decrypt($request->link);
        $email = OrderWebsiteEmail::find($request->id);
        $email->link_click = $email->link_click + 1;
        $email->save();
        
        $url = ReviewWebsiteLink::find($link);
        
        return \Redirect::to($url->link);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $link = ReviewWebsiteLink::find($request->id);
        if(isset($link->id))
        {
            return response()->json([
                'data'=>$link,
                'status'=>true,
                'status_code'=>200
            ]);
        }
        else
        {
            return response()->json([
                'data'=>'',
                'status'=>false,
                'status_code'=>400
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendLink(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'website'=>'required',
            'customer_email'=>'required'
        ]);
        
        if($validator->passes())
        {
            $email = OrderWebsiteEmail::where('order_id',$request->order_id)->where('email',$request->customer_email)->first();
            if(!isset($email->id))
            {
                $email = new OrderWebsiteEmail;
            }
            $email->order_id = $request->order_id;
            $email->link_id = $request->website;
            $email->email = $request->customer_email;
            $email->link_click = 0;
            $email->save();
            
            $link = ReviewWebsiteLink::find($request->website);
            $details = [
                'title'=>'We are very happy to deliver your vehicle! Kindly give us some reviews on the website which is given below :)',
                'link'=>$link->id,
                'name'=>$link->name,
                'id'=>$email->id
            ];
            
            Mail::to($request->customer_email)->send(new SendLinkEmail($details));
            
            return response()->json([
                'message'=>'Save',
                'status'=>true,
                'status_code'=>200
            ]);
        }
        else
        {
            return response()->json([
                'error'=>$validator->errors(),
                'status'=>false,
                'status_code'=>400
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $link = ReviewWebsiteLink::find($id);
        if(isset($link->id))
        {
            if($link->status == 1)
            {
                $link->status = 0;
                $link->save();
                $msg = $link->name.' has been unactive successfully!';
                return back()->with('err',$msg);
            }
            else
            {
                $link->status = 1;
                $link->save();
                $msg = $link->name.' has been active successfully!';
                return back()->with('msg',$msg);
            }
        }
        else
        {
            return back()->with('err','Somathing went wrong');
        }
    }
}
