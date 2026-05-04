<table id="reviewsTable" class="table table-bordered table-striped text-nowrap key-buttons">
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
                    <img width='' src='{{ $row->screenshot }}'
                        style="border: 1px solid #5da6f2; border-radius: 5px;">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
