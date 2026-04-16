@props(['field', 'title'])

@php
    $currentSort = request('sort', 'id');
    $currentDir  = request('direction', 'desc');
    $isActive    = $currentSort === $field;
    $nextDir     = ($isActive && $currentDir === 'asc') ? 'desc' : 'asc';
    $icon        = $isActive ? ($currentDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted opacity-50';
@endphp

<th {{ $attributes->merge(['class' => 'py-3']) }}>
    <a href="{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $nextDir]) }}" 
       class="d-flex align-items-center text-primary font-weight-bold text-decoration-none shadow-none"
       style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
        {{ $title }}
        <span class="ml-2">
            <i class="fas {{ $icon }}" style="font-size: 0.9rem;"></i>
        </span>
    </a>
</th>
