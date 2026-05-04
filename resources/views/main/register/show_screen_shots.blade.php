<div class="table-responsive">
    <table class="table table-bordered table-striped text-nowrap key-buttons">
        <thead>
        <tr>
            <th class="border-bottom-0">SNo#</th>
            <th class="border-bottom-0">Time</th>
            <th class="border-bottom-0">Screen Shot</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $ss as $key => $val)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{\Carbon\Carbon::parse($val->created_at)->format('M, Y d h:i A')}}</td>
                <td>
                    <a href="{{$val->image_url}}" target="_blank" title="To see the screen shot clearly click the image and refresh it.">
                        <img src="{{$val->image_url}}" style="width:150px;height:200px;" />
                    </a>
                    <br>
                    <span class="text-secondary">To see the screen shot clearly click the image and refresh it.</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
    <div class="d-flex justify-content-between">
        <div class="text-secondary my-auto">
            Showing {{ $ss->firstItem() ?? 0 }} to {{ $ss->lastItem() ?? 0 }} from total {{$ss->total()}} entries
        </div>
        <div>
            {{  $ss->links() }}
        </div>
        
    </div>
</div>