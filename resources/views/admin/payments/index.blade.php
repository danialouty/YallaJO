@extends('layouts.dashboard')

@section('title', 'المدفوعات | يلا جو')
@section('dashboard-theme', 'admin')
@section('dashboard-name', 'لوحة الإدارة')
@section('dashboard-title', 'المدفوعات')

@section('content')
    <div class="space-y-6 pb-8">
        <x-ui.card padding="lg">
            <form action="{{ route('admin.payments.index') }}" method="GET" class="grid gap-4 md:grid-cols-3">
                <x-ui.input name="search" id="admin-payment-search" label="رقم الدفعة أو الحجز"
                    :value="$filters['search'] ?? null" inputmode="numeric" dir="ltr" />

                @foreach (['status' => ['الحالة', $statuses], 'method' => ['طريقة الدفع', $methods]] as $field => [$label, $options])
                    <div class="space-y-2">
                        <label for="admin-payment-{{ $field }}" class="block text-sm font-semibold">{{ $label }}</label>
                        <select id="admin-payment-{{ $field }}" name="{{ $field }}"
                            @error($field) aria-invalid="true" aria-describedby="admin-payment-{{ $field }}-error" @enderror
                            class="block w-full rounded-control border border-outline bg-surface py-3 ps-4 pe-10 text-sm
                                   rtl:bg-position-[left_0.5rem_center] focus:border-brand focus:ring-brand">
                            <option value="">الكل</option>
                            @foreach ($options as $value => $option)
                                <option value="{{ $value }}" @selected(($filters[$field] ?? null) === $value)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error($field)
                            <p id="admin-payment-{{ $field }}-error" role="alert" class="text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="flex flex-wrap items-center gap-4 md:col-span-3">
                    <x-ui.button type="submit">تطبيق الفلاتر</x-ui.button>
                    <a href="{{ route('admin.payments.index') }}" class="rounded-control text-sm font-semibold text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">مسح الفلاتر</a>
                </div>
            </form>
        </x-ui.card>

        <x-ui.card padding="none">
            @if ($payments->isEmpty())
                <p class="p-6 text-muted">لا توجد مدفوعات في هذه الصفحة.</p>
            @else
                <div class="overflow-x-auto rounded-card">
                    <table class="w-full text-start text-sm">
                        <caption class="sr-only">سجلات المدفوعات</caption>
                        <thead class="bg-canvas text-ink">
                            <tr>
                                @foreach (['الدفعة', 'الحجز', 'الطريقة', 'الحالة', 'المبلغ', 'تاريخ الدفع', 'التفاصيل'] as $heading)
                                    <th scope="col" class="whitespace-nowrap px-4 py-3 text-start">{{ $heading }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline/40">
                            @foreach ($payments as $payment)
                                <tr>
                                    <td class="px-4 py-3 font-semibold" dir="ltr">#{{ $payment->id }}</td>
                                    <td class="px-4 py-3" dir="ltr">#{{ $payment->booking_id }}</td>
                                    <td class="px-4 py-3">{{ $methods[$payment->method] ?? $payment->method }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">{{ $statuses[$payment->status] ?? $payment->status }}</td>
                                    <td class="whitespace-nowrap px-4 py-3"><bdi>{{ number_format((float) $payment->total_amount, 2) }}</bdi> د.أ</td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        @if ($payment->paid_at)
                                            <time datetime="{{ $payment->paid_at->toDateString() }}" dir="ltr">{{ $payment->paid_at->toDateString() }}</time>
                                        @else
                                            <span class="text-muted">غير مسجّل</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="rounded-control text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                                            عرض<span class="sr-only"> الدفعة رقم {{ $payment->id }}</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>

        {{ $payments->links() }}
    </div>
@endsection
