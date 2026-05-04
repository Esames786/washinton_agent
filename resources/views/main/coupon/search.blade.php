<div>
    <table class="table table-bordered table-striped text-nowrap key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">Created Date</th>
                <th class="border-bottom-0">Client EMAIL</th>
                <th class="border-bottom-0">Coupon Number</th>
                <th class="border-bottom-0">Coupon Price</th>
                <th class="border-bottom-0">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $key => $value)
                <tr>
                    <td>{{\Carbon\Carbon::parse($value->created_at)->format('M, d Y')}}</td>
                    <td>{{$value->coupon_email}}</td>
                    <td>{{$value->coupon_number}}</td>
                    <td>${{$value->coupon_price}}</td>
                    <td>
                        @if($value->status == 1)
                        <span class="badge badge-success text-light">Used</span>
                        @else
                        <span class="badge badge-danger">Not Used</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $coupons->firstItem() ?? 0 }} to {{ $coupons->lastItem() ?? 0 }} from total {{$coupons->total()}} entries
    </div>
    <div>
        {{$coupons->links()}}
    </div>
</div>