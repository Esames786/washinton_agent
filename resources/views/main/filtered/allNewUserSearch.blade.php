<?php

function get_pstatus2($id)
{
    $ret = '';
    if ($id == 0) {
        $ret = 'NEW';
    } elseif ($id == 1) {
        $ret = 'Interested';
    } elseif ($id == 2) {
        $ret = 'FollowMore';
    } elseif ($id == 3) {
        $ret = 'AskingLow';
    } elseif ($id == 4) {
        $ret = 'NotInterested';
    } elseif ($id == 5) {
        $ret = 'NoResponse';
    } elseif ($id == 6) {
        $ret = 'TimeQuote';
    } elseif ($id == 7) {
        $ret = 'PaymentMissing';
    } elseif ($id == 8) {
        $ret = 'Booked';
    } elseif ($id == 9) {
        $ret = 'Listed';
    } elseif ($id == 10) {
        $ret = 'Dispatch';
    } elseif ($id == 11) {
        $ret = 'Pickup';
    } elseif ($id == 12) {
        $ret = 'Delivered';
    } elseif ($id == 13) {
        $ret = 'Completed';
    } elseif ($id == 14) {
        $ret = 'Cancel';
    } elseif ($id == 15) {
        $ret = 'Deleted';
    } elseif ($id == 16) {
        $ret = 'OwesMoney';
    } elseif ($id == 17) {
        $ret = 'CarrierUpdate';
    } elseif ($id == 18) {
        $ret = 'OnApproval';
    } elseif ($id == 19) {
        $ret = 'On Approval Cancelled';
    }
    return $ret;
}

function putX($digits, $status, $num)
{
    $val = $num;
    if ($status == 0) {
        if ($digits == 0) {
            $val = $num;
        } elseif ($digits == 1) {
            $val = '(x' . substr($num, -12);
        } elseif ($digits == 2) {
            $val = '(xx' . substr($num, -11);
        } elseif ($digits == 3) {
            $val = '(xxx) ' . substr($num, -8);
        } elseif ($digits == 4) {
            $val = '(xxx) x' . substr($num, -7);
        } elseif ($digits == 5) {
            $val = '(xxx) xx' . substr($num, -6);
        } elseif ($digits == 6) {
            $val = '(xxx) xxx-' . substr($num, -4);
        } elseif ($digits == 7) {
            $val = '(xxx) xxx-x' . substr($num, -3);
        } elseif ($digits == 8) {
            $val = '(xxx) xxx-xx' . substr($num, -2);
        } elseif ($digits == 9) {
            $val = '(xxx) xxx-xxx' . substr($num, -1);
        } elseif ($digits == 10) {
            $val = '(xxx) xxx-xxxx';
        }
    } elseif ($status == 1) {
        if ($digits == 0) {
            $val = $num;
        } elseif ($digits == 1) {
            $val = substr($num, 0, 13) . 'x';
        } elseif ($digits == 2) {
            $val = substr($num, 0, 12) . 'xx';
        } elseif ($digits == 3) {
            $val = substr($num, 0, 11) . 'xxx ';
        } elseif ($digits == 4) {
            $val = substr($num, 0, 10) . 'xxxx';
        } elseif ($digits == 5) {
            $val = substr($num, 0, 8) . 'x-xxxx';
        } elseif ($digits == 6) {
            $val = substr($num, 0, 7) . 'xx-xxxx';
        } elseif ($digits == 7) {
            $val = substr($num, 0, 6) . 'xxx-xxxx';
        } elseif ($digits == 8) {
            $val = substr($num, 0, 3) . 'x) xxx-xxxx';
        } elseif ($digits == 9) {
            $val = substr($num, 0, 2) . 'xx) xxx-xxxx';
        } elseif ($digits == 10) {
            $val = '(xxx) xxx-xxxx';
        }
    } elseif ($status == 2) {
        if ($digits == 0) {
            $val = $num;
        } elseif ($digits == 1) {
            $val = substr($num, 0, 7) . 'x' . substr($num, -6);
        } elseif ($digits == 2) {
            $val = substr($num, 0, 7) . 'xx' . substr($num, -5);
        } elseif ($digits == 3) {
            $val = substr($num, 0, 6) . 'xxx' . substr($num, -5);
        } elseif ($digits == 4) {
            $val = substr($num, 0, 3) . 'x) xxx' . substr($num, -5);
        } elseif ($digits == 5) {
            $val = substr($num, 0, 3) . 'x) xxx-x' . substr($num, -3);
        } elseif ($digits == 6) {
            $val = substr($num, 0, 3) . 'x) xxx-xx' . substr($num, -2);
        } elseif ($digits == 7) {
            $val = substr($num, 0, 2) . 'xx) xxx-xx' . substr($num, -2);
        } elseif ($digits == 8) {
            $val = substr($num, 0, 2) . 'xx) xxx-xxx' . substr($num, -1);
        } elseif ($digits == 9) {
            $val = '(xxx) xxx-xxx' . substr($num, -1);
        } elseif ($digits == 10) {
            $val = '(xxx) xxx-xxxx';
        }
    }
    return $val;
}
?>
<div class="table-responsive" style="padding-bottom: 1rem;">
    <table class="table table-bordered table-sm" style="width:100%" role="grid">
        <thead>
            <tr>
                <th class="border-bottom-0">Order Id</th>
                <th class="border-bottom-0">Order Taker Name</th>
                <th class="border-bottom-0">Client Name</th>
                <th class="border-bottom-0">Delivery</th>
                <th class="border-bottom-0">Zip</th>
                <th class="border-bottom-0">Vehicle Name</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Last Time</th>
                <th class="border-bottom-0">Referral</th>
            </tr>
        </thead>
        <tbody class="showData">
            @foreach ($order as $key => $value)
                <tr>
                    <td>
                        <a href="/searchData?search={{ $value->id }}">{{ $value->id }}</a>
                    </td>
                    <?php
                    $name = '';
                    if (isset($value->orderTaker)) {
                        $name = $value->orderTaker->slug ? $value->orderTaker->slug : $value->orderTaker->name;
                    }
                    ?>
                    <td>{{ $name }}</td>
                    <td>{{ $value->oname }}</td>
                    <td>{{ $value->destinationcity }}</td>
                    <td>{{ $value->destinationzip }}</td>
                    <td>{{ $value->ymk }}</td>
                    <td>{{ get_pstatus2($value->pstatus) }}</td>
                    <td>Created At:
                        <br>{{ \Carbon\Carbon::parse($value->created_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}
                    </td>
                    <td>
                        @if ($value->how_did_you_find_us === 'existing_customer')
                            Referred by Existing Customer
                        @elseif($value->how_did_you_find_us === 'social_media')
                            Social Media
                        @elseif($value->how_did_you_find_us === 'review_platform')
                            Review Platform
                        @endif
                        <br>
                        @if ($value->found_on_referral_phone)
                            <?php
                            $digits = \App\PhoneDigit::first();
                            $new = putX($digits->hide_digits, $digits->left_right_status, $value->found_on_referral_phone);
                            ?>
                            <a href="/searchData?search={{ $value->found_on_referral_phone }}">
                                {{ $new }}
                            </a>
                        @endif
                        @if ($value->found_on_social_media)
                            {{ $value->found_on_social_media }}
                        @endif
                        @if ($value->found_on_review_platform)
                            {{ $value->found_on_review_platform }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $order->firstItem() ?? 0 }} to {{ $order->lastItem() ?? 0 }} from total {{ $order->total() }}
        entries
    </div>
    <div class="pagi">
        {{ $order->links() }}
    </div>
</div>
