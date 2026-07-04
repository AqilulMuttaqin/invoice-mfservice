@php
    $badges = [
        'received' => [
            'label' => 'Received',
            'class' => 'bg-primary',
        ],
        'repairing' => [
            'label' => 'Repairing',
            'class' => 'bg-warning',
        ],
        'completed' => [
            'label' => 'Completed',
            'class' => 'bg-success',
        ],
        'picked_up' => [
            'label' => 'Picked Up',
            'class' => 'bg-info',
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'class' => 'bg-danger',
        ],
    ];

    $badge = $badges[$status] ?? [
        'label' => ucfirst(str_replace('_', ' ', $status)),
        'class' => 'bg-secondary',
    ];
@endphp

<span class="badge {{ $badge['class'] }}">
    {{ $badge['label'] }}
</span>
