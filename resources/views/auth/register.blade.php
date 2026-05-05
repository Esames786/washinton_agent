<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — DayDispatch</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body { background: #f0f4f8; }
        .register-card {
            max-width: 560px;
            margin: 60px auto;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }
        .register-header {
            background: #062e39;
            color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 20px 28px 14px;
            border-bottom: 4px solid #8fc445;
        }
        .register-header h4 { margin: 0; font-weight: 700; letter-spacing: 0.5px; }
        .register-header p  { margin: 4px 0 0; font-size: 13px; color: #b0c4c8; }
        .register-body { padding: 28px; background: #fff; border-radius: 0 0 10px 10px; }
        .type-btn {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 14px 10px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fff;
        }
        .type-btn:hover, .type-btn.selected {
            border-color: #8fc445;
            background: #f4fbe8;
        }
        .type-btn .icon { font-size: 28px; display: block; margin-bottom: 4px; }
        .type-btn .label { font-weight: 600; font-size: 14px; color: #062e39; }
        .type-btn .sub   { font-size: 11px; color: #888; }
        .btn-register {
            background: #8fc445;
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 10px 0;
            font-size: 15px;
            border-radius: 6px;
            width: 100%;
        }
        .btn-register:hover { background: #7aad38; color: #fff; }
    </style>
</head>
<body>

<div class="register-card">
    <div class="register-header">
        <h4>Create Your Account</h4>
        <p>DayDispatch Portal — Request access as an Agent or Carrier</p>
    </div>
    <div class="register-body">

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Error message --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" novalidate>
            @csrf

            {{-- Account Type --}}
            <div class="form-group mb-4">
                <label class="font-weight-bold d-block mb-2">Account Type <span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-6">
                        <label class="type-btn w-100 {{ old('signup_type') === 'agent' ? 'selected' : '' }}" id="btn-agent">
                            <input type="radio" name="signup_type" value="agent" class="d-none"
                                   {{ old('signup_type', 'agent') === 'agent' ? 'checked' : '' }}>
                            <span class="icon">🧑‍💼</span>
                            <span class="label">Agent</span>
                            <span class="sub">Order Taker / Sales</span>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="type-btn w-100 {{ old('signup_type') === 'carrier' ? 'selected' : '' }}" id="btn-carrier">
                            <input type="radio" name="signup_type" value="carrier" class="d-none"
                                   {{ old('signup_type') === 'carrier' ? 'checked' : '' }}>
                            <span class="icon">🚛</span>
                            <span class="label">Carrier</span>
                            <span class="sub">Dispatcher / Driver</span>
                        </label>
                    </div>
                </div>
                @error('signup_type')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Full Name --}}
            <div class="form-group">
                <label>First Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Enter your first name" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Last Name --}}
            <div class="form-group">
                <label>Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                       class="form-control @error('last_name') is-invalid @enderror"
                       placeholder="Enter your last name" required>
                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Slug / Display Name --}}
            <div class="form-group">
                <label>Display Name / Slug <span class="text-danger">*</span>
                    <small class="text-muted font-weight-normal">(shown in system, e.g. john.doe)</small>
                </label>
                <input type="text" name="slug" value="{{ old('slug') }}"
                       class="form-control @error('slug') is-invalid @enderror"
                       placeholder="e.g. john.doe or JohnD" required>
                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label>Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Enter your email" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label>Password <span class="text-danger">*</span></label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimum 8 characters" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label>Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Re-enter password" required>
            </div>

            {{-- Phone --}}
            <div class="form-group">
                <label>Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="form-control @error('phone') is-invalid @enderror"
                       placeholder="Enter phone number" required>
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Address --}}
            <div class="form-group">
                <label>Full Address <span class="text-danger">*</span></label>
                <textarea name="address" rows="2"
                          class="form-control @error('address') is-invalid @enderror"
                          placeholder="Enter your complete address" required>{{ old('address') }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn-register mt-2">Create Account</button>

            <p class="text-center text-muted small mt-3 mb-0">
                Already have an account?
                <a href="{{ url('/loginn') }}" style="color:#062e39;font-weight:600;">Sign In</a>
            </p>
        </form>
    </div>
</div>

<script>
    // Highlight selected account type card
    function updateTypeCards() {
        document.querySelectorAll('input[name="signup_type"]').forEach(function(radio) {
            var card = radio.closest('.type-btn');
            if (radio.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }

    // Run on change
    document.querySelectorAll('input[name="signup_type"]').forEach(function(radio) {
        radio.addEventListener('change', updateTypeCards);
    });

    // Run immediately on page load so Agent is highlighted by default
    updateTypeCards();
</script>

</body>
</html>
