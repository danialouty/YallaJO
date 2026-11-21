@props(['name', 'label', 'options', 'value' => null, 'required' => false])

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" @required($required)
        @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
        class="block w-full rounded-control border border-outline bg-surface py-3 ps-4 pe-10 text-sm rtl:bg-position-[left_0.5rem_center] focus:border-brand focus:ring-brand">
        <option value="">اختر</option>
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" @selected(is_scalar(old($name, $value)) && (string) old($name, $value) === (string) $key)>{{ $option }}</option>
        @endforeach
    </select>
    @error($name)
        <p id="{{ $name }}-error" class="text-sm text-danger">{{ $message }}</p>
    @enderror
</div>
