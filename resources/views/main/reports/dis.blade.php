
<div class="table-responsive">
    {{--example1--}}
    <table class="table table-bordered table-sm" style="width:100%" id="" role="grid"
           aria-describedby="example1_info">
        <thead>
        <tr>
            <th class="border-bottom-0">s.no</th>
            <th class="border-bottom-0">User</th>
            <th class="border-bottom-0">List</th>
            <th class="border-bottom-0">Auction Update</th>
            <th class="border-bottom-0">Carrier Update</th>
            <th class="border-bottom-0">Dispatch</th>
            <th class="border-bottom-0">Pickup</th>
            <th class="border-bottom-0">Delivery</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dispatchers as $key => $val)
            <tr>
                <td>{{ $dispatchers->firstItem()+$key }}</td>
                <td>{{ $val->name }}</td>
                <td>
                    <?php
                        $listed = \App\report::where('userId',$val->id)->where('pstatus',9)->whereBetween('created_at',array($from,$to))->count()
                    ?>
                    {{ $listed }}
                </td>
                <td></td>
                <td>
                    <?php
                        $carrier_update_count = \App\carrier::where('userId',$val->id)->whereBetween('created_at',array($from,$to))->count()
                    ?>
                    {{ $carrier_update_count }}
                </td>
                <td>
                    <?php
                        $dispatch = \App\report::where('userId',$val->id)->where('pstatus',10)->whereBetween('created_at',array($from,$to))->count()
                    ?>
                    {{ $dispatch }}
                </td>
                <td>
                    <?php
                        $pickup = \App\report::where('userId',$val->id)->where('pstatus',11)->whereBetween('created_at',array($from,$to))->count()
                    ?>
                    {{ $pickup }}
                </td>
                <td>
                    <?php
                        $delivery = \App\report::where('userId',$val->id)->where('pstatus',12)->whereBetween('created_at',array($from,$to))->count()
                    ?>
                    {{ $delivery }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $dispatchers->links() }}
</div>