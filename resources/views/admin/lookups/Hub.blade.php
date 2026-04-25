@extends('admin.layouts.app')

@section('content')

{{-- ── PAGE HEADER ─────────────────────────────────────────── --}}
<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">
                <i class="fas fa-sliders" style="margin-right:.35rem"></i>
                Configuration
            </div>
            <h1 class="page-title">Master <em>Data</em></h1>
            <p class="page-subtitle">Manage all lookup tables, reference data, and master lists used across Vivah.</p>
        </div>
    </div>
</div>

@php
$groups = [
    [
        'title' => 'Spiritual & Heritage',
        'icon'  => 'fa-om',
        'color' => 'rose',
        'items' => [
            ['type' => 'religions',      'label' => 'Religions',      'icon' => 'fa-om',          'desc' => 'Hindu, Muslim, Christian, Sikh…'],
            ['type' => 'castes',         'label' => 'Castes',         'icon' => 'fa-layer-group', 'desc' => 'Primary caste categories'],
            ['type' => 'sub-castes',     'label' => 'Sub Castes',     'icon' => 'fa-sitemap',     'desc' => 'Detailed sub-caste breakdown'],
            ['type' => 'gotras',         'label' => 'Gotras',         'icon' => 'fa-tree',        'desc' => 'Gotra lineages & clans'],
            ['type' => 'communities',    'label' => 'Communities',    'icon' => 'fa-users',       'desc' => 'Community groups'],
            ['type' => 'mother-tongues', 'label' => 'Mother Tongues', 'icon' => 'fa-language',    'desc' => 'Languages & dialects'],
        ],
    ],
    [
        'title' => 'Astrology',
        'icon'  => 'fa-star-and-crescent',
        'color' => 'gold',
        'items' => [
            ['type' => 'rashis',     'label' => 'Rashis',     'icon' => 'fa-star', 'desc' => 'Moon signs (zodiac rashis)'],
            ['type' => 'nakshatras', 'label' => 'Nakshatras', 'icon' => 'fa-moon', 'desc' => 'Birth star nakshatra list'],
        ],
    ],
    [
        'title' => 'Education & Career',
        'icon'  => 'fa-briefcase',
        'color' => 'blue',
        'items' => [
            ['type' => 'education-levels',     'label' => 'Education Levels',  'icon' => 'fa-graduation-cap',      'desc' => '10th, 12th, Graduate, PG…'],
            ['type' => 'professions',          'label' => 'Professions',       'icon' => 'fa-briefcase',           'desc' => 'Job roles & career types'],
            ['type' => 'annual-income-ranges', 'label' => 'Income Ranges',     'icon' => 'fa-indian-rupee-sign',   'desc' => 'Salary bands & income brackets'],
        ],
    ],
    [
        'title' => 'Geography',
        'icon'  => 'fa-globe',
        'color' => 'green',
        'items' => [
            ['type' => 'countries', 'label' => 'Countries', 'icon' => 'fa-globe',        'desc' => 'All supported countries'],
            ['type' => 'states',    'label' => 'States',    'icon' => 'fa-map',          'desc' => 'States & provinces'],
            ['type' => 'cities',    'label' => 'Cities',    'icon' => 'fa-city',         'desc' => 'City master list'],
            ['type' => 'areas',     'label' => 'Areas',     'icon' => 'fa-location-dot', 'desc' => 'Area / locality list'],
        ],
    ],
];
@endphp

@foreach($groups as $group)

{{-- Group Separator --}}
<div class="section-sep">
    <div class="section-sep-line"></div>
    <div class="section-sep-label">
        <i class="fas {{ $group['icon'] }}" style="margin-right:.4rem;color:var(--rose)"></i>
        {{ $group['title'] }}
    </div>
    <div class="section-sep-line"></div>
</div>

<div class="lookup-hub-grid">
    @foreach($group['items'] as $item)
    <a href="{{ route("admin.lookups.{$item['type']}.index") }}" class="lookup-hub-card">
        <div class="lookup-hub-icon stat-icon {{ $group['color'] }}">
            <i class="fas {{ $item['icon'] }}"></i>
        </div>
        <div class="lookup-hub-text">
            <div class="lookup-hub-name">{{ $item['label'] }}</div>
            <div class="lookup-hub-desc">{{ $item['desc'] }}</div>
        </div>
        <i class="fas fa-chevron-right lookup-hub-arrow"></i>
    </a>
    @endforeach
</div>

@endforeach

<style>
.lookup-hub-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
    margin-bottom: .5rem;
}
.lookup-hub-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.125rem 1.25rem;
    display: flex; align-items: center; gap: 1rem;
    text-decoration: none;
    transition: all .2s ease;
    box-shadow: 0 2px 8px var(--card-shadow);
}
.lookup-hub-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px var(--card-shadow);
    border-color: var(--rose-light);
}
.lookup-hub-card:hover .lookup-hub-arrow {
    color: var(--rose);
    transform: translateX(3px);
}
.lookup-hub-icon {
    width: 42px; height: 42px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.lookup-hub-text { flex: 1; min-width: 0; }
.lookup-hub-name {
    font-weight: 600; font-size: .875rem;
    color: var(--text-primary); margin-bottom: .15rem;
}
.lookup-hub-desc {
    font-size: .75rem; color: var(--text-muted);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.lookup-hub-arrow {
    font-size: .75rem; color: var(--border);
    transition: all .2s ease; flex-shrink: 0;
}
</style>

@endsection