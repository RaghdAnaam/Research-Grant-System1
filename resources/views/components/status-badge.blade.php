@props(['status'])

@php
    $class = match ($status) {
        'Completed' => 'badge-completed',
        'In Progress' => 'badge-progress',
        'Delayed' => 'badge-delayed',
        default => 'badge-pending',
    };
@endphp

<span {{ $attributes->merge(['class' => "status-badge {$class}"]) }}>{{ $status }}</span>
