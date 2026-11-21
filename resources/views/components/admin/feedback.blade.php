@if (session('success'))
    <p role="status" class="mb-4 rounded-control border border-forest/30 bg-forest/10 p-4 text-sm text-forest">{{ session('success') }}</p>
@endif
@if (session('error'))
    <p role="alert" class="mb-4 rounded-control border border-danger/30 bg-danger/5 p-4 text-sm text-danger">{{ session('error') }}</p>
@endif
@error('confirm_delete')
    <p role="alert" class="mb-4 rounded-control border border-danger/30 bg-danger/5 p-4 text-sm text-danger">{{ $message }}</p>
@enderror
