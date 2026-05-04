@include('partials.mainsite_pages.return_function')

<style>
    #reviewsTable {
        table-layout: fixed;
        width: 100%;
    }

    #reviewsTable th,
    #reviewsTable td {
        white-space: normal;
        word-wrap: break-word;
    }
</style>
<div class="col-sm-12">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order #.</th>
                <th>Name</th>
                <th>Review</th>
                <th>Created At</th>
                <th>Screenshot</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->orderId }}</td>
                    <td>{{ $row->user->name }}</td>
                    <td>
                        @if ($row->review)
                            {!! '<b>Review:</b> ' . $row->review !!} <br>
                        @endif
                        @if ($row->website)
                            {!! '<b>Website:</b> ' . $row->website !!} <br>
                        @endif
                        @if ($row->website_other)
                            {!! '<b>Website Other:</b> ' . $row->website_other !!} <br>
                        @endif
                        @if ($row->client_rating)
                            {!! '<b>Client Rating:</b> ' . $row->client_rating !!} <br>
                        @endif
                        @if ($row->website_link)
                            {!! '<b>Website Link:</b> ' . $row->website_link !!} <br>
                        @endif
                        @if ($row->additional)
                            {!! '<b>Additional:</b> ' . $row->additional !!}
                        @endif
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($row->created_at)->format('M,d Y') }}
                    </td>
                    <td>
                        @if ($row->screenshot)
                            <img width="100px" height="100px" src="{{ $row->screenshot }}"
                                style="border: 1px solid #5da6f2; border-radius: 5px;">
                        @else
                            No Image
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <div class="row">
        <div class="col-sm-6 text-secondary my-auto">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{ $data->total() }}
            entries
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
            {{ $data->links() }}
        </div>
    </div>

</div>
<script>
    var getData22 = (id, status) => {
        $.ajax({
            url: "{{ url('/get_shipment_status_order_detail3') }}",
            type: "GET",
            data: {
                id: id,
                status: status
            },
            dataType: "HTML",
            success: function(res) {
                $("#detail_order").html('');
                $("#detail_order").html(res);
            }
        });
    }
</script>
