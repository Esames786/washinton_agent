@include('partials.mainsite_pages.return_function')

<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 maincol">
    <div class="table">
        <div class="row">
            <div class="col-md-12 mainheading">
                Move To Storage
                
                <span class="rounded-circle green delpik ml-2" style="height: 30px;width: 32px;display: flex;align-items: center;justify-content: center;font-size:11px;">{{$count}}</span>
            </div>
        </div>
        <div class="row smheading">
            <div class="col-md-6 col-6 columns">
                Order ID
            </div>
            <!--<div class="col-md-4 col-4 columns">-->
            <!--    OT/DIS-->
            <!--</div>-->
            <div class="col-md-6 col-6 columns">DATE</div>
        </div>
        <div class="row">
            <div class="customers col-sm-12">
            @foreach($data as $val)
                <?php
                    $late1 = \Carbon\Carbon::parse($val->storage_date);
                    if($late1->diffInDays() > 0)
                    {
                        if($val->pstatus == 11)
                        {
                            $days = $late1->diffInDays();
                        }
                        else
                        {
                            $com_date1 = \Carbon\Carbon::parse($val->storage_move_date);
                            $diff1 = $late1->diffInDays($com_date1);
                            if($diff1 > 0)
                            {
                                $days = $diff1;
                            }
                            else
                            {
                                $days = 0;
                            }
                        }
                    }
                    else
                    {
                        $days = 0;
                    }
                ?>
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <a target="_blank" href="/searchData?search={{$val->id}}">{{$val->id}}</a>
                            <select name="" id="" style="width: 75%;">
                                @if(isset($val->order_taker_id))
                                <option value="{{get_user_name($val->order_taker_id)}}">{{get_user_name($val->order_taker_id)}}</option>
                                @endif
                                @if(isset($val->dispatcher_id))
                                <option value="{{get_user_name($val->dispatcher_id)}}">{{get_user_name($val->dispatcher_id)}}</option>
                                @endif
                            </select>
                            <!--<span class="yellow delpik mx-auto mt-1 " onclick="getData({{$val->id}})" style="cursor:pointer;" title="View Detail"  data-toggle="modal" data-target="#exampleModal"><i class="fa fa-eye"></i></span>-->
                        </div>
                        <!--<div class="col-md-4 col-4">-->
                            
                        <!--</div>-->
                        <div class="col-md-6 col-6">
                            <span>{{\Carbon\Carbon::parse($val->storage_date)->format('M,d Y')}}</span>
                            @if(isset($val->storage_move_date))
                            <span>{{\Carbon\Carbon::parse($val->storage_move_date)->format('M,d Y')}}</span>
                            @endif
        
                            <?php 
                                        if($days == 0)
                                        {
                            ?>
                                            <span class="green delpik mx-auto" style="font-size:11px;"><?php echo $days  ?> day</span>
                            <?php 
                                        } 
                                        else if($days == 1)
                                        {
                            ?>
                                            <span class="yellow delpik mx-auto" style="font-size:11px;"><?php echo $days  ?> day</span>
                            <?php
                                        }
                                        else
                                        {
                            ?>
                                            <span class="red delpik mx-auto" style="font-size:11px;"><?php echo $days  ?> days</span>
                            <?php
                                        }
                            ?>
                        </div>
                    </div>
            @endforeach
        </div>
        </div>
    </div>
</div>