{{-- resources/views/components/status-badge.blade.php --}}

@props(['status'])

@php
$classes = match($status) {
    'pending' => 'badge-pending',
    'diterima' => 'badge-diterima',
    'ditolak' => 'badge-ditolak',
    default => 'badge-pending'
};

$text = match($status) {
    'pending' => 'Pending',
    'diterima' => 'Diterima',
    'ditolak' => 'Ditolak',
    default => ucfirst($status)
};

$icon = match($status) {
    'pending' => 'bi-clock-history',
    'diterima' => 'bi-check-circle-fill',
    'ditolak' => 'bi-x-circle-fill',
    default => 'bi-circle'
};
@endphp

<span class="badge-status {{ $classes }}">
    <i class="bi {{ $icon }}"></i> {{ $text }}
</span>