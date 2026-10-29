@include('partials.mainsite_pages.return_function')
@php
    // Same permission chain every listing partial uses — the panel decides which column the
    // permission codes come from, so an agent on Karachi is never judged by Islamabad's grants.
    $__dmU = Auth::user();
    $__dmP = \App\user_setting::where('user_id', $__dmU->id)->value('penal_type') ?: 1;
    $__dmA = explode(',', (string) $__dmU->accessForPanel($__dmP));

    $__dmAdmin = in_array((int) $__dmU->role, [1, 9], true);
    $digits = \App\PhoneDigit::first();
    $check_call = check_call();
@endphp

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th width="50">SR. #</th>
                <th>DEALER</th>
                <th>PHONE</th>
                <th>LOCATION</th>
                <th>RATING</th>
                <th>EMAIL</th>
                <th>ASSIGNED TO</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($data as $key => $val)
            <tr>
                <td>{{ $data->firstItem() + $key }}</td>

                <td>
                    <strong>{{ $val->name }}</strong>
                    @if ($val->website)
                        <br><a href="{{ $val->website }}" target="_blank" rel="noopener" class="small">DealerRater profile</a>
                    @endif
                    @if ($val->inventory_cars)
                        <br><span class="badge badge-light">{{ number_format($val->inventory_cars) }} cars</span>
                    @endif
                </td>

                <td>
                    @php
                        // Four numbers in the source; show every one the dealer actually has.
                        $__phones = array_filter([
                            'Main'    => $val->phone,
                            'New'     => $val->phone2,
                            'Used'    => $val->phone3,
                            'Service' => $val->phone4,
                        ]);
                    @endphp
                    @if (empty($__phones))
                        <span class="text-muted">— no phone —</span>
                    @else
                        @foreach ($__phones as $__label => $__num)
                            @php
                                $masked = $digits
                                    ? putX($digits->hide_digits, $digits->left_right_status, $__num)
                                    : $__num;
                            @endphp
                            <div class="mb-1">
                                <small class="text-muted">{{ $__label }}</small><br>
                                <a onclick="dmCall('{{ base64_encode($__num) }}', '{{ $val->id }}')"
                                   class="btn btn-outline-info mobile"
                                   style="padding:3px 6px;font-size:14px;cursor:pointer;">
                                    <i class="fa fa-phone"></i> {{ $masked }}
                                </a>
                                <a onclick="dmMsg('{{ base64_encode($__num) }}')"
                                   class="btn btn-outline-success sms"
                                   style="padding:3px 6px;font-size:14px;cursor:pointer;">
                                    <i class="fa fa-comment"></i>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </td>

                <td>
                    {{ $val->address }}
                    @if ($val->city || $val->states)
                        <br>{{ trim(collect([$val->city, $val->states, $val->zip])->filter()->implode(', ')) }}
                    @endif
                </td>

                <td>
                    @if ($val->rating)
                        <span class="badge badge-warning">&#9733; {{ number_format($val->rating, 1) }}</span>
                        <br><small>{{ number_format($val->reviews ?? 0) }} reviews</small>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                <td>
                    {{-- The source carries no email addresses, so agents add them as they learn them. --}}
                    <input type="email" class="form-control form-control-sm dm-field"
                           data-id="{{ $val->id }}" data-field="email"
                           value="{{ $val->email }}" placeholder="add email">
                </td>

                <td>
                    @if ($val->user_id && $val->user)
                        <span class="badge badge-info">{{ $val->user->slug ?: $val->user->name }}</span>
                    @else
                        <span class="text-muted">Unassigned</span>
                    @endif
                </td>

                <td style="white-space:nowrap;">
                    <button type="button" class="btn btn-sm btn-primary dm-history"
                            data-id="{{ $val->id }}" data-name="{{ $val->name }}">
                        Add / View History
                    </button>
                    @if ($val->history_count ?? $val->history()->count())
                        <span class="badge badge-success">{{ $val->history()->count() }}</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted">No dealers match this filter.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-2">
    {{ $data->links() }}
</div>
