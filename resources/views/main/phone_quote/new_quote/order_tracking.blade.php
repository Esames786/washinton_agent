@extends('layouts.print_layout')

@section('template_title')
Track Order
@endsection

@section('content')
<style>
    body {
        background: #f5f7fb;
    }
    .tracking-wrapper {
        max-width: 850px;
        margin: 30px auto;
    }
    .tracking-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .tracking-header {
        background: #062e39;
        color: #fff;
        padding: 18px 24px;
        border-bottom: 5px solid #8fc445;
    }
    .tracking-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }
    .tracking-body {
        padding: 24px;
    }
    .status-badge {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 15px;
        color: #fff;
        background: #8fc445;
        margin-bottom: 20px;
    }
    .detail-box {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 700;
        color: #062e39;
        width: 35%;
    }
    .detail-value {
        width: 65%;
        text-align: right;
        color: #333;
    }
    @media (max-width: 768px) {
        .detail-row {
            flex-direction: column;
            gap: 6px;
        }
        .detail-label,
        .detail-value {
            width: 100%;
            text-align: left;
        }
    }
</style>

<div class="container tracking-wrapper">
    <div class="tracking-card">
        <div class="tracking-header">
            <h2>Order Tracking #{{ $autoorder->id }}</h2>
        </div>

        <div class="tracking-body">
            <div class="text-center mb-4">
                <div class="status-badge">
                    Current Status: {{ $currentStatus }}
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-row">
                    <div class="detail-label">Order #</div>
                    <div class="detail-value">{{ $autoorder->id }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Customer Name</div>
                    <div class="detail-value">{{ $autoorder->oname }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">
                        {{ substr_replace(str_repeat('x', strlen($autoorder->ophone) - 3), substr($autoorder->ophone, -3), -3) }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Vehicle</div>
                    <div class="detail-value">
                        {{ $autoorder->year }} {{ $autoorder->make }} {{ $autoorder->model }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Origin</div>
                    <div class="detail-value">{{ $autoorder->originzsc }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Destination</div>
                    <div class="detail-value">{{ $autoorder->destinationzsc }}</div>
                </div>



                <div class="detail-row">
                    <div class="detail-label">Vehicle Condition</div>
                    <div class="detail-value">
                        {{ $autoorder->condition == '1' ? 'Running' : 'Not Running' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Booking Price</div>
                    <div class="detail-value">${{ number_format((float)$autoorder->payment, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
