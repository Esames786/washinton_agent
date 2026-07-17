@php $uid = $c->id ?? 'new'; @endphp
<div class="form-group">
    <label>Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required maxlength="150"
           value="{{ old('name', $c->name ?? '') }}" placeholder="e.g. Healthcare Products & Services">
</div>

<div class="form-group">
    <label>Employment Category <span class="text-danger">*</span></label>
    <select name="employment_category" class="form-control js-emp-cat" data-uid="{{ $uid }}" required>
        <option value="work_from_home" {{ old('employment_category', $c->employment_category ?? 'work_from_home') === 'work_from_home' ? 'selected' : '' }}>Work From Home</option>
        <option value="in_house" {{ old('employment_category', $c->employment_category ?? '') === 'in_house' ? 'selected' : '' }}>In-House / On-Site</option>
    </select>
    <small class="text-muted">Work From Home is always Commission Only.</small>
</div>

<div class="form-group js-paytype-wrap" id="paytypeWrap{{ $uid }}">
    <label>Default Pay Type <small class="text-muted">(In-House only; blank = all allowed)</small></label>
    <select name="default_pay_type" class="form-control">
        <option value="">All pay types</option>
        <option value="salary_only" {{ old('default_pay_type', $c->default_pay_type ?? '') === 'salary_only' ? 'selected' : '' }}>Salary Only</option>
        <option value="commission_only" {{ old('default_pay_type', $c->default_pay_type ?? '') === 'commission_only' ? 'selected' : '' }}>Commission Only</option>
        <option value="salary_and_commission" {{ old('default_pay_type', $c->default_pay_type ?? '') === 'salary_and_commission' ? 'selected' : '' }}>Salary + Commission</option>
    </select>
</div>

<div class="form-row">
    <div class="form-group col-6">
        <label>Icon <small class="text-muted">(emoji)</small></label>
        <input type="text" name="icon" class="form-control" maxlength="16" value="{{ old('icon', $c->icon ?? '') }}" placeholder="🏥">
    </div>
    <div class="form-group col-6">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1" {{ old('status', $c->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $c->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label>Description <small class="text-muted">(optional)</small></label>
    <input type="text" name="description" class="form-control" maxlength="255" value="{{ old('description', $c->description ?? '') }}">
</div>

<script>
(function () {
    var sel = document.querySelector('.js-emp-cat[data-uid="{{ $uid }}"]');
    var wrap = document.getElementById('paytypeWrap{{ $uid }}');
    if (!sel || !wrap) return;
    function sync() { wrap.style.display = (sel.value === 'in_house') ? '' : 'none'; }
    sel.addEventListener('change', sync);
    sync();
})();
</script>
