<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Payment Successful</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #f4f7fb 0%, #eaf2ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .success-wrapper {
            width: 100%;
            max-width: 640px;
            animation: fadeInUp 0.8s ease;
        }

        .success-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 50px 35px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(18, 38, 63, 0.12);
            position: relative;
            overflow: hidden;
        }

        .success-card::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -120px;
            width: 260px;
            height: 260px;
            background: rgba(40, 167, 69, 0.08);
            border-radius: 50%;
        }

        .success-card::after {
            content: "";
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 220px;
            height: 220px;
            background: rgba(0, 123, 255, 0.06);
            border-radius: 50%;
        }

        .icon-wrap {
            position: relative;
            z-index: 2;
            width: 110px;
            height: 110px;
            margin: 0 auto 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745, #43d17a);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 30px rgba(40, 167, 69, 0.28);
            animation: popIn 0.7s ease;
        }

        .icon-wrap span {
            font-size: 52px;
            color: #fff;
            line-height: 1;
            animation: pulse 1.8s infinite;
        }

        .success-title {
            position: relative;
            z-index: 2;
            font-size: 34px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 14px;
        }

        .success-text {
            position: relative;
            z-index: 2;
            font-size: 17px;
            line-height: 1.7;
            color: #6b7280;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        .highlight-box {
            position: relative;
            z-index: 2;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px 20px;
            margin-top: 18px;
            font-size: 15px;
            color: #374151;
        }

        .highlight-box strong {
            color: #111827;
        }

        .btn-home {
            position: relative;
            z-index: 2;
            display: inline-block;
            margin-top: 28px;
            background: linear-gradient(135deg, #0d6efd, #3b82f6);
            color: #fff;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.22);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(13, 110, 253, 0.28);
        }

        .tick-ring {
            position: absolute;
            width: 140px;
            height: 140px;
            border: 2px dashed rgba(40, 167, 69, 0.25);
            border-radius: 50%;
            top: 35px;
            left: 50%;
            transform: translateX(-50%);
            animation: rotateRing 9s linear infinite;
            z-index: 1;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(35px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes popIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            70% {
                transform: scale(1.08);
                opacity: 1;
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.08);
            }
        }

        @keyframes rotateRing {
            from {
                transform: translateX(-50%) rotate(0deg);
            }
            to {
                transform: translateX(-50%) rotate(360deg);
            }
        }

        @media (max-width: 576px) {
            .success-card {
                padding: 38px 22px;
                border-radius: 18px;
            }

            .success-title {
                font-size: 28px;
            }

            .success-text {
                font-size: 15px;
            }

            .icon-wrap {
                width: 92px;
                height: 92px;
            }

            .icon-wrap span {
                font-size: 42px;
            }
        }
    </style>
</head>
<body>
<div class="success-wrapper">
    <div class="success-card">
        <div class="tick-ring"></div>

        <div class="icon-wrap">
            <span>👍</span>
        </div>

        <h1 class="success-title">Order Payment Successful</h1>

        <p class="success-text">
            Your payment has been processed successfully and your order has been saved in our system.
            Thank you for completing your transaction with us.
        </p>

        <div class="highlight-box">
            <strong>Status:</strong> Payment Received Successfully<br>
            <strong>Order:</strong> Saved and confirmed for further processing
        </div>

    </div>
</div>
</body>
</html>
