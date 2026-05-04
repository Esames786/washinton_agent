@include('partials.mainsite_pages.return_function')
<div class="table-responsive">
    <table id="example1" class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">ORDER #</th>
            <th class="border-bottom-0">PICKUP</th>
            <th class="border-bottom-0">DELIVERY</th>
            <th class="border-bottom-0">ORDER PRICE</th>
            <th class="border-bottom-0">DATE</th>
        </tr>
        </thead>
        <tbody>
        @php
            $val = "attendance_date";
            $val2 = "logout";

        @endphp
        @foreach($data as $row)
          <tr>
              <td>{{$row->id }}</td>
              <td>
                  {{$row->originzsc }}
                  <br>
                   {{$row->oaddress }}
              </td>
              <td>
                  {{$row->destinationzsc }}
                  <br>
                  {{$row->daddress }}
              </td>
              <td>{{$row->payment }}  </td>
              <td>{{\Carbon\Carbon::parse($row->created_at)->format('M,d Y h:i A')}}</td>
          </tr>
       @endforeach
        </tbody>

    </table>
 @if(!empty($data))
    {{  $data->links() }}
 @endif

</div>
<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>




