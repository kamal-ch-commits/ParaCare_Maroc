@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="name">{{ __('common.name') }}</label>
        <input type="text" id="name" name="name" value="{{ old('name', $supplier->name) }}" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label" for="phone">{{ __('common.phone') }}</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label" for="email">{{ __('common.email') }}</label>
        <input type="email" id="email" name="email" value="{{ old('email', $supplier->email) }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label" for="address">{{ __('common.address') }}</label>
        <textarea id="address" name="address" class="form-control" rows="2">{{ old('address', $supplier->address) }}</textarea>
    </div>
</div>
<div class="d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ __('common.save') }}</button>
    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">{{ __('common.cancel') }}</a>
</div>
