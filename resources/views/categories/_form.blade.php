@csrf
<div class="mb-3">
    <label class="form-label" for="name">{{ __('common.name') }}</label>
    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
</div>
<div class="mb-3">
    <label class="form-label" for="description">{{ __('common.description') }}</label>
    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
</div>
<div class="d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ __('common.save') }}</button>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">{{ __('common.cancel') }}</a>
</div>
