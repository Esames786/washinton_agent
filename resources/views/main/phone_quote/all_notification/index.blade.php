@extends('layouts.innerpages')
@include('partials.mainsite_pages.return_function')

@section('template_title')
    All Notification
@endsection


@section('content')

    <style>
        i#comment_style {
            line-height: 1 !important;
        }
    </style>

    <div class="row mt-8" style=" display: grid; place-items: center; ">
        <div class="col-md-10 mt-4">
            <div class="card">
                <div class="card-header" style=" justify-content: center; ">
                    <h3>All Notification</h3>
                </div>
                <div class="card-body">
                    <?php
                    function get_name($id)
                    {
                        $setting = App\general_setting::first();
                        $query = \App\User::where('id', $id)->first();
                        if (!empty($query)) {
                            if($query->slug)
                            {
                                return $query->slug;
                            }
                            return $query->name.' '.$query->last_name;
                        } else {
                            return '';
                        }


                    }

                    $date = date('Y-m-d');

                    $data1 = \App\report::orderBy('created_at', 'desc')->paginate(50);
                    ?>
                    @foreach($data1 as $val2)
                        <a href="#" class="dropdown-item border-bottom d-flex pl-4">
                            <div class="notifyimg bg-info-transparent text-info pt-3"><i
                                        class="ti-comment-alt" id="comment_style"></i></div>
                            <div>
                                <div class="font-weight-normal1">
                                           <span
                                                   class="text-info">{{get_name($val2->userId)}}
                                           </span>
                                    change status to :
                                    {{get_pstatus($val2->pstatus)}} ORDER ID :
                                    {{$val2->orderId}}

                                </div>
                                <div class="small text-muted">{{\Carbon\Carbon::parse($val2->created_at)->format('M,Y d h:i:s A')}}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="card-footer">
                    {{$data1->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
