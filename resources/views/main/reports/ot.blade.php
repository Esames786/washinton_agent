<div class="table-responsive">
    {{--example1--}}
    <table class="table table-bordered table-sm" style="width:100%" id="" role="grid"
           aria-describedby="example1_info">
        <thead>
        <tr>
            <th class="border-bottom-0">s.no</th>
            <th class="border-bottom-0">User</th>
            <th class="border-bottom-0">Quote Create<BR></th>
            <th class="border-bottom-0">Order Book</th>
            <th class="border-bottom-0">Cancel</th>
            <th class="border-bottom-0">Completed</th>
            <th class="border-bottom-0">Histroy Update</th>
            <th class="border-bottom-0">Click On Call</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order_takers as $key => $val)
            @php
                $count_click_count = 0;
                foreach ($val->count_click as $c){
                    $count_click_count += $c->total_clicks;
                }
            @endphp
            <tr>
                <td>{{ $val->id }}</td>
                <td>{{ $val->name ."($val->slug)" }}</td>
                <td>
                    <?php
                    $quote_create = \App\report::where('userId',$val->id)->where('pstatus',0)->whereBetween('created_at',array($from,$to))->groupBy('orderId', 'pstatus', 'userId')->selectRaw('orderId, pstatus, userId, COUNT(*) as count')->get()->toArray();
                    ?>
                    {{ count($quote_create) }}
                </td>
                <td>
                    <?php
                    $order_book = \App\report::where('userId',$val->id)->whereIn('pstatus',[7,8,18])->whereBetween('created_at',array($from,$to))->groupBy('orderId', 'pstatus', 'userId')->selectRaw('orderId, pstatus, userId, COUNT(*) as count')->get()->toArray();
                    ?>
                    {{ count($order_book) }}
                </td>
                <td>
                    <?php
                    $cancel_order = \App\report::where('userId',$val->id)->where('pstatus',14)->whereBetween('created_at',array($from,$to))->groupBy('orderId', 'pstatus', 'userId')->selectRaw('orderId, pstatus, userId, COUNT(*) as count')->get()->toArray();
                    ?>
                    {{ count($cancel_order) }}
                </td>
                <td>
                    <?php // count($val->order_book) ?>

                    <?php
                    $completed = \App\report::where('userId',$val->id)->where('pstatus',13)->whereBetween('created_at',array($from,$to))->groupBy('orderId', 'pstatus', 'userId')->selectRaw('orderId, pstatus, userId, COUNT(*) as count')->get()->toArray();
                    ?>
                    {{ count($completed) }}
                </td>
                <td>

                    <?php

                    //                    $orderhistory = \App\call_history::
                    //                     whereBetween('created_at',array($from,$to))
                    //                    ->where('userId', $val->id)
                    //                    ->where('pstatus','!=',0)->orderby('orderId','desc')
                    //                    ->count();

                    $orderhistory = count($val->call_history);

                    ?>

                    <button data-user_id="{{$val->id}}" data-order_id="{{$val->orderId}}" type="button" class="btn btn-link btn-sm btn-show-history" data-toggle="modal" data-target="#historyModal">
                        <i class="fa fa-eye"></i>{{ $orderhistory}}
                    </button>
                </td>

                <td>{{ $count_click_count }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $order_takers->links() }}
</div>