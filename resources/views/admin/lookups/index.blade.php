@extends('admin.layouts.app')

@php
    /*
    |--------------------------------------------------------------------------
    | Lookup Meta — drives the entire view dynamically
    |--------------------------------------------------------------------------
    */
    $meta = [
        'religions' => [
            'label'       => 'Religions',
            'singular'    => 'Religion',
            'icon'        => 'fa-om',
            'color'       => 'rose',
            'columns'     => ['name', 'sort_order', 'is_active'],
            'parents'     => [],
            'extra_fields'=> [],
        ],
        'castes' => [
            'label'       => 'Castes',
            'singular'    => 'Caste',
            'icon'        => 'fa-layer-group',
            'color'       => 'gold',
            'columns'     => ['religion', 'name', 'sort_order', 'is_active'],
            'parents'     => [
                ['key' => 'religion_id', 'label' => 'Religion', 'model' => 'religions', 'required' => true],
            ],
            'extra_fields'=> [],
        ],
        'sub-castes' => [
            'label'       => 'Sub Castes',
            'singular'    => 'Sub Caste',
            'icon'        => 'fa-sitemap',
            'color'       => 'rose',
            'columns'     => ['caste', 'name', 'is_active'],
            'parents'     => [
                ['key' => 'religion_id', 'label' => 'Religion', 'model' => 'religions', 'required' => true, 'cascade_for' => 'caste_id'],
                ['key' => 'caste_id',    'label' => 'Caste',    'model' => 'castes',    'required' => true, 'depends_on' => 'religion_id'],
            ],
            'extra_fields'=> [],
        ],
        'gotras' => [
            'label'       => 'Gotras',
            'singular'    => 'Gotra',
            'icon'        => 'fa-dharmachakra',
            'color'       => 'gold',
            'columns'     => ['religion', 'name', 'is_active'],
            'parents'     => [
                ['key' => 'religion_id', 'label' => 'Religion', 'model' => 'religions', 'required' => false],
            ],
            'extra_fields'=> [],
        ],
        'communities' => [
            'label'       => 'Communities',
            'singular'    => 'Community',
            'icon'        => 'fa-people-group',
            'color'       => 'blue',
            'columns'     => ['religion', 'name', 'sort_order', 'is_active'],
            'parents'     => [
                ['key' => 'religion_id', 'label' => 'Religion', 'model' => 'religions', 'required' => false],
            ],
            'extra_fields'=> [],
        ],
        'mother-tongues' => [
            'label'       => 'Mother Tongues',
            'singular'    => 'Mother Tongue',
            'icon'        => 'fa-language',
            'color'       => 'teal',
            'columns'     => ['name', 'sort_order', 'is_active'],
            'parents'     => [],
            'extra_fields'=> [],
        ],
        'rashis' => [
            'label'       => 'Rashis',
            'singular'    => 'Rashi',
            'icon'        => 'fa-star-and-crescent',
            'color'       => 'purple',
            'columns'     => ['name', 'english_name', 'sort_order', 'is_active'],
            'parents'     => [],
            'extra_fields'=> [
                ['key' => 'english_name', 'label' => 'English Name', 'type' => 'text', 'required' => false],
            ],
        ],
        'nakshatras' => [
            'label'       => 'Nakshatras',
            'singular'    => 'Nakshatra',
            'icon'        => 'fa-moon',
            'color'       => 'purple',
            'columns'     => ['name', 'sort_order', 'is_active'],
            'parents'     => [],
            'extra_fields'=> [],
        ],
        'education-levels' => [
            'label'       => 'Education Levels',
            'singular'    => 'Education Level',
            'icon'        => 'fa-graduation-cap',
            'color'       => 'blue',
            'columns'     => ['name', 'sort_order', 'is_active'],
            'parents'     => [],
            'extra_fields'=> [],
        ],
        'professions' => [
            'label'       => 'Professions',
            'singular'    => 'Profession',
            'icon'        => 'fa-briefcase',
            'color'       => 'green',
            'columns'     => ['name', 'sort_order', 'is_active'],
            'parents'     => [],
            'extra_fields'=> [],
        ],
        // FIX: columns use min_amount/max_amount to match AnnualIncomeRange model
        'annual-income-ranges' => [
            'label'       => 'Annual Income Ranges',
            'singular'    => 'Income Range',
            'icon'        => 'fa-indian-rupee-sign',
            'color'       => 'gold',
            'columns'     => ['label', 'min_amount', 'max_amount', 'currency', 'sort_order', 'is_active'],
            'parents'     => [],
            'extra_fields'=> [
                ['key' => 'label',      'label' => 'Label',     'type' => 'text',   'required' => true],
                ['key' => 'min_amount', 'label' => 'Min Value', 'type' => 'number', 'required' => true],
                ['key' => 'max_amount', 'label' => 'Max Value', 'type' => 'number', 'required' => false],
                ['key' => 'currency',   'label' => 'Currency',  'type' => 'text',   'required' => false, 'default' => 'INR'],
            ],
        ],
        'countries' => [
            'label'       => 'Countries',
            'singular'    => 'Country',
            'icon'        => 'fa-flag',
            'color'       => 'blue',
            'columns'     => ['name', 'iso_code', 'phone_code', 'sort_order', 'is_active'],
            'parents'     => [],
            'extra_fields'=> [
                ['key' => 'iso_code',   'label' => 'ISO Code',   'type' => 'text', 'required' => true],
                ['key' => 'phone_code', 'label' => 'Phone Code', 'type' => 'text', 'required' => false],
            ],
        ],
        'states' => [
            'label'       => 'States',
            'singular'    => 'State',
            'icon'        => 'fa-map',
            'color'       => 'teal',
            'columns'     => ['country', 'name', 'code', 'sort_order', 'is_active'],
            'parents'     => [
                ['key' => 'country_id', 'label' => 'Country', 'model' => 'countries', 'required' => true],
            ],
            'extra_fields'=> [
                ['key' => 'code', 'label' => 'State Code', 'type' => 'text', 'required' => false],
            ],
        ],
        'cities' => [
            'label'       => 'Cities',
            'singular'    => 'City',
            'icon'        => 'fa-city',
            'color'       => 'rose',
            'columns'     => ['state', 'name', 'sort_order', 'is_active'],
            'parents'     => [
                ['key' => 'country_id', 'label' => 'Country', 'model' => 'countries', 'required' => true,  'cascade_for' => 'state_id'],
                ['key' => 'state_id',   'label' => 'State',   'model' => 'states',    'required' => true,  'depends_on' => 'country_id'],
            ],
            'extra_fields'=> [],
        ],
        'areas' => [
            'label'       => 'Areas',
            'singular'    => 'Area',
            'icon'        => 'fa-location-dot',
            'color'       => 'green',
            'columns'     => ['city', 'name', 'pincode', 'is_active'],
            'parents'     => [
                ['key' => 'state_id', 'label' => 'State', 'model' => 'states', 'required' => true, 'cascade_for' => 'city_id'],
                ['key' => 'city_id',  'label' => 'City',  'model' => 'cities', 'required' => true, 'depends_on'  => 'state_id'],
            ],
            'extra_fields'=> [
                ['key' => 'pincode', 'label' => 'Pincode', 'type' => 'text', 'required' => false],
            ],
        ],
    ];

    $current   = $meta[$type] ?? $meta['religions'];
    $label     = $current['label'];
    $singular  = $current['singular'];
    $icon      = $current['icon'];
    $color     = $current['color'];
    $columns   = $current['columns'];
    $parents   = $current['parents'];
    $extraFields = $current['extra_fields'];

    // Color maps
    $colorMap = [
        'rose'   => 'var(--rose)',
        'gold'   => 'var(--gold)',
        'green'  => 'var(--success)',
        'blue'   => 'var(--info)',
        'purple' => '#8a5aaa',
        'teal'   => '#3a9090',
    ];
    $accentColor = $colorMap[$color] ?? 'var(--rose)';

    // Human-readable column headers
    // FIX: added min_amount / max_amount headers
    $colHeaders = [
        'name'         => 'Name',
        'label'        => 'Label',
        'religion'     => 'Religion',
        'caste'        => 'Caste',
        'country'      => 'Country',
        'state'        => 'State',
        'city'         => 'City',
        'english_name' => 'English Name',
        'iso_code'     => 'ISO Code',
        'phone_code'   => 'Phone Code',
        'code'         => 'Code',
        'pincode'      => 'Pincode',
        'min_amount'   => 'Min (₹)',
        'max_amount'   => 'Max (₹)',
        'currency'     => 'Currency',
        'sort_order'   => 'Order',
        'is_active'    => 'Status',
    ];

    // Which column to use as the primary "name" field for display
    $primaryField = in_array('label', $columns) ? 'label' : 'name';
@endphp

@section('content')

{{-- ── PAGE HEADER ──────────────────────────────────────────── --}}
<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">
                <i class="fas {{ $icon }}" style="margin-right:.35rem;"></i>
                Master Data
            </div>
            <h1 class="page-title">{{ $label }} <em>Management</em></h1>
            <p class="page-subtitle">
                Manage all {{ strtolower($label) }} used across the platform.
                {{ $records->total() }} total records.
            </p>
        </div>
        <div style="display:flex;gap:.75rem;align-items:center;">
            <button class="btn btn-outline btn-sm" onclick="exportCSV()">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn btn-rose btn-sm" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Add {{ $singular }}
            </button>
        </div>
    </div>
</div>

{{-- ── SESSION ALERTS ───────────────────────────────────────── --}}
@if(session('success'))
<div class="alert-banner alert-success" id="sessionAlert">
    <i class="fas fa-circle-check"></i>
    {{ session('success') }}
    <button onclick="this.parentElement.remove()" class="alert-close">×</button>
</div>
@endif
@if(session('error'))
<div class="alert-banner alert-error" id="sessionAlert">
    <i class="fas fa-circle-exclamation"></i>
    {{ session('error') }}
    <button onclick="this.parentElement.remove()" class="alert-close">×</button>
</div>
@endif

<div class="lookup-tabs-wrap">
    <div class="lookup-tabs">
        @foreach([
            'religions'           => ['fa-om',                 'Religions',      'religions'],
            'castes'              => ['fa-layer-group',        'Castes',         'castes'],
            'sub-castes'          => ['fa-sitemap',            'Sub Castes',     'sub_castes'],
            'gotras'              => ['fa-dharmachakra',       'Gotras',         'gotras'],
            'communities'         => ['fa-people-group',       'Communities',    'communities'],
            'mother-tongues'      => ['fa-language',           'Mother Tongues', 'mother_tongues'],
            'rashis'              => ['fa-star-and-crescent',  'Rashis',         'rashis'],
            'nakshatras'          => ['fa-moon',               'Nakshatras',     'nakshatras'],
            'education-levels'    => ['fa-graduation-cap',     'Education',      'education_levels'],
            'professions'         => ['fa-briefcase',          'Professions',    'professions'],
            'annual-income-ranges'=> ['fa-indian-rupee-sign',  'Income Ranges',  'annual_income_ranges'],
            'countries'           => ['fa-flag',               'Countries',      'countries'],
            'states'              => ['fa-map',                'States',         'states'],
            'cities'              => ['fa-city',               'Cities',         'cities'],
            'areas'               => ['fa-location-dot',       'Areas',          'areas'],
        ] as $tabType => [$tabIcon, $tabLabel, $routeName])
        <a href="{{ route('admin.lookups.' . $routeName . '.index') }}"
           class="lookup-tab {{ $type === $tabType ? 'active' : '' }}">
            <i class="fas {{ $tabIcon }}"></i>
            <span>{{ $tabLabel }}</span>
        </a>
        @endforeach
    </div>
</div>

{{-- ── MAIN TABLE CARD ──────────────────────────────────────── --}}
<div class="card" style="margin-top:1.25rem;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas {{ $icon }}" style="color:{{ $accentColor }}"></i>
            {{ $label }}
        </h3>
        <div class="card-actions">
            <div class="search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="tableSearch"
                    class="form-control search-input"
                    placeholder="Search {{ strtolower($label) }}…"
                    oninput="filterTable(this.value)">
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table id="lookupTable">
            <thead>
                <tr>
                    <th>#</th>
                    @foreach($columns as $col)
                        <th>{{ $colHeaders[$col] ?? ucfirst(str_replace('_',' ',$col)) }}</th>
                    @endforeach
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr class="record-row" data-search="{{ strtolower($record->{$primaryField} ?? '') }}">
                    <td style="color:var(--text-muted);font-size:.75rem;">
                        {{ $records->firstItem() + $loop->index }}
                    </td>

                    @foreach($columns as $col)
                    <td>
                        @if($col === 'is_active')
                            @if($record->is_active)
                                <span class="badge badge-active"><i class="fas fa-circle" style="font-size:.45rem;"></i> Active</span>
                            @else
                                <span class="badge badge-inactive"><i class="fas fa-circle" style="font-size:.45rem;"></i> Inactive</span>
                            @endif

                        @elseif($col === 'religion')
                            <span class="rel-pill">
                                <i class="fas fa-om"></i>
                                {{ $record->religion?->name ?? '—' }}
                            </span>

                        @elseif($col === 'caste')
                            <span class="rel-pill">
                                <i class="fas fa-layer-group"></i>
                                {{ $record->caste?->name ?? '—' }}
                            </span>

                        @elseif($col === 'country')
                            <span class="rel-pill">
                                <i class="fas fa-flag"></i>
                                {{ $record->country?->name ?? ($record->state?->country?->name ?? '—') }}
                            </span>

                        @elseif($col === 'state')
                            <span class="rel-pill">
                                <i class="fas fa-map"></i>
                                {{ $record->state?->name ?? '—' }}
                            </span>

                        @elseif($col === 'city')
                            <span class="rel-pill">
                                <i class="fas fa-city"></i>
                                {{ $record->city?->name ?? '—' }}
                            </span>

                        {{-- FIX: was min_value/max_value — now min_amount/max_amount --}}
                        @elseif($col === 'min_amount' || $col === 'max_amount')
                            <span style="font-family:monospace;font-size:.8rem;">
                                {{ $record->{$col} !== null ? '₹'.number_format($record->{$col}) : '—' }}
                            </span>

                        @elseif($col === 'sort_order')
                            <span class="sort-badge">{{ $record->sort_order ?? 0 }}</span>

                        @elseif($col === 'name')
                            <span style="font-weight:600;">{{ $record->name }}</span>

                        @elseif($col === 'label')
                            <span style="font-weight:600;">{{ $record->label }}</span>

                        @else
                            {{ $record->{$col} ?? '—' }}
                        @endif
                    </td>
                    @endforeach

                    <td style="text-align:right;">
                        <div style="display:flex;gap:.375rem;justify-content:flex-end;">
                            <button class="btn btn-ghost btn-sm"
                                title="Toggle Status"
                                onclick="toggleStatus({{ $record->id }}, {{ $record->is_active ? 'true' : 'false' }})">
                                <i class="fas {{ $record->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"
                                   style="color:{{ $record->is_active ? 'var(--success)' : 'var(--text-muted)' }}"></i>
                            </button>
                            <button class="btn btn-ghost btn-sm"
                                title="Edit"
                                onclick="openEditModal({{ $record->id }}, {{ $record->toJson() }})">
                                <i class="fas fa-pen" style="color:var(--info);"></i>
                            </button>
                            <button class="btn btn-ghost btn-sm"
                                title="Delete"
                                onclick="confirmDelete({{ $record->id }}, '{{ addslashes($record->{$primaryField} ?? '') }}')">
                                <i class="fas fa-trash" style="color:var(--danger);"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($columns) + 2 }}" class="empty-state">
                        <div class="empty-inner">
                            <i class="fas {{ $icon }} empty-icon"></i>
                            <p>No {{ strtolower($label) }} found.</p>
                            <button class="btn btn-rose btn-sm" onclick="openCreateModal()">
                                <i class="fas fa-plus"></i> Add First {{ $singular }}
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if($records->hasPages())
    <div class="pagination-wrap">
        {{ $records->appends(request()->query())->links('admin.lookups.pagination') }}
    </div>
    @endif
</div>


{{-- ══════════════════════════════════════════════════════════
     CREATE MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="createModal" onclick="closeIfOverlay(event,'createModal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="modal-title">
                <div class="modal-icon" style="background:{{ $accentColor }}">
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div>
                    <h4>Add {{ $singular }}</h4>
                    <p>Fill in the details below</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('createModal')">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <form method="POST" action="{{ url('/admin/lookups/'.$type) }}" id="createForm">
            @csrf
            <div class="modal-body">
                @include('admin.lookups._form_fields', [
                    'formId'       => 'create',
                    'record'       => null,
                    'parents'      => $parents,
                    'extraFields'  => $extraFields,
                    'type'         => $type,
                    'primaryField' => $primaryField,
                    'columns'      => $columns,
                ])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('createModal')">Cancel</button>
                <button type="submit" class="btn btn-rose">
                    <i class="fas fa-plus"></i> Create {{ $singular }}
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     EDIT MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="editModal" onclick="closeIfOverlay(event,'editModal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="modal-title">
                <div class="modal-icon" style="background:{{ $accentColor }}">
                    <i class="fas fa-pen"></i>
                </div>
                <div>
                    <h4>Edit {{ $singular }}</h4>
                    <p>Update record details</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('editModal')">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-body" id="editFormBody">
                {{-- Filled dynamically by JS --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn btn-rose">
                    <i class="fas fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="deleteModal" onclick="closeIfOverlay(event,'deleteModal')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="modal-title">
                <div class="modal-icon" style="background:var(--danger)">
                    <i class="fas fa-trash"></i>
                </div>
                <div>
                    <h4>Delete Record</h4>
                    <p id="deleteModalMsg">Are you sure?</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('deleteModal')">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="danger-alert">
                <i class="fas fa-triangle-exclamation"></i>
                This action cannot be undone. All data linked to this record may also be affected.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('deleteModal')">Cancel</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="background:var(--danger);color:#fff;">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════════════════ --}}
<style>
/* ── ALERT BANNERS ──────────────────────────────────────────── */
.alert-banner {
    display: flex; align-items: center; gap: .75rem;
    padding: .875rem 1.25rem; border-radius: 12px;
    margin-bottom: 1.25rem; font-size: .85rem; font-weight: 500;
    animation: fadeUp .4s ease;
}
.alert-success { background: rgba(74,140,106,.1); border: 1px solid rgba(74,140,106,.3); color: var(--success); }
.alert-error   { background: rgba(184,76,76,.1);  border: 1px solid rgba(184,76,76,.3);  color: var(--danger); }
.alert-close   { margin-left: auto; background: none; border: none; cursor: pointer; font-size: 1.1rem; opacity: .6; }
.alert-close:hover { opacity: 1; }

/* ── LOOKUP TABS ────────────────────────────────────────────── */
.lookup-tabs-wrap {
    overflow-x: auto; padding-bottom: .25rem;
    -webkit-overflow-scrolling: touch;
}
.lookup-tabs-wrap::-webkit-scrollbar { height: 4px; }
.lookup-tabs-wrap::-webkit-scrollbar-thumb { background: rgba(200,113,90,.3); border-radius: 4px; }

.lookup-tabs {
    display: flex; gap: .375rem; min-width: max-content;
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 14px; padding: .375rem;
}
.lookup-tab {
    display: flex; align-items: center; gap: .45rem;
    padding: .5rem .875rem; border-radius: 10px;
    font-size: .75rem; font-weight: 600;
    color: var(--text-muted); text-decoration: none;
    transition: all .2s ease; white-space: nowrap;
}
.lookup-tab:hover { background: var(--bg-secondary); color: var(--text-primary); }
.lookup-tab.active {
    background: var(--rose-grad); color: #fff;
    box-shadow: 0 3px 10px rgba(200,113,90,.35);
}
.lookup-tab i { font-size: .8rem; }

/* ── SEARCH ─────────────────────────────────────────────────── */
.search-wrap { position: relative; }
.search-icon {
    position: absolute; left: .75rem; top: 50%;
    transform: translateY(-50%); color: var(--text-muted); font-size: .8rem;
}
.search-input {
    padding-left: 2.25rem !important;
    width: 220px; font-size: .8rem;
}

/* ── TABLE ENHANCEMENTS ─────────────────────────────────────── */
.rel-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .2rem .6rem; border-radius: 6px;
    background: var(--bg-secondary); color: var(--text-secondary);
    font-size: .75rem; font-weight: 500;
}
.rel-pill i { font-size: .65rem; opacity: .7; }
.sort-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 8px;
    background: var(--bg-secondary); color: var(--text-muted);
    font-size: .75rem; font-weight: 700;
}

/* ── EMPTY STATE ────────────────────────────────────────────── */
.empty-state { text-align: center; padding: 3rem 1rem !important; }
.empty-inner { display: flex; flex-direction: column; align-items: center; gap: 1rem; }
.empty-icon  { font-size: 2.5rem; color: var(--border); }
.empty-state p { color: var(--text-muted); font-size: .9rem; }

/* ── PAGINATION WRAP ────────────────────────────────────────── */
.pagination-wrap {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end;
}

/* ── MODALS ─────────────────────────────────────────────────── */
.modal-overlay {
    position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,0,0,.55); backdrop-filter: blur(4px);
    display: none; align-items: center; justify-content: center;
    padding: 1rem;
}
.modal-overlay.open { display: flex; animation: fadeIn .2s ease; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

.modal-box {
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 20px; width: 100%; max-width: 520px;
    box-shadow: 0 24px 60px rgba(0,0,0,.25);
    animation: slideUp .3s cubic-bezier(.4,0,.2,1);
    max-height: 90vh; overflow-y: auto;
}
.modal-sm { max-width: 420px; }
@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}

.modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: 1px solid var(--border);
}
.modal-title { display: flex; align-items: center; gap: .875rem; }
.modal-icon {
    width: 40px; height: 40px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem; flex-shrink: 0;
}
.modal-title h4 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.25rem; font-weight: 700;
    color: var(--text-primary); margin: 0;
}
.modal-title p { font-size: .75rem; color: var(--text-muted); margin: 0; }
.modal-close {
    width: 32px; height: 32px; border-radius: 8px;
    border: 1px solid var(--border); background: var(--card-bg);
    color: var(--text-muted); cursor: pointer; font-size: .9rem;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s ease;
}
.modal-close:hover { background: var(--bg-secondary); color: var(--text-primary); }

.modal-body   { padding: 1.25rem 1.5rem; }
.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end; gap: .75rem;
}

/* ── DANGER ALERT ───────────────────────────────────────────── */
.danger-alert {
    background: rgba(184,76,76,.08); border: 1px solid rgba(184,76,76,.2);
    color: var(--danger); border-radius: 10px;
    padding: .875rem 1rem; font-size: .82rem;
    display: flex; align-items: flex-start; gap: .625rem; line-height: 1.5;
}
.danger-alert i { margin-top: .1rem; flex-shrink: 0; }

/* ── TOGGLE STATUS FORM ─────────────────────────────────────── */
.toggle-form { display: inline; }
</style>


{{-- ══════════════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════════════ --}}
<script>
// ── CONFIG passed from PHP ────────────────────────────────────
const LOOKUP_TYPE   = '{{ $type }}';
const BASE_URL      = '{{ url("/admin/lookups/".$type) }}';
const AJAX_BASE     = '{{ url("/admin/api/lookups") }}';
const PRIMARY_FIELD = '{{ $primaryField }}';

// Parent config for cascade dropdowns
const PARENT_CONFIG = @json($parents);
const EXTRA_FIELDS  = @json($extraFields);

// ── MODAL HELPERS ─────────────────────────────────────────────
function openModal(id)  {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
function closeIfOverlay(e, id) {
    if (e.target === document.getElementById(id)) closeModal(id);
}

// ── CREATE MODAL ──────────────────────────────────────────────
function openCreateModal() {
    document.getElementById('createForm').reset();
    document.querySelectorAll('#createModal select[data-depends]').forEach(sel => {
        sel.innerHTML = '<option value="">— select parent first —</option>';
        sel.disabled = true;
    });
    openModal('createModal');
}

// ── EDIT MODAL ────────────────────────────────────────────────
function openEditModal(id, record) {
    const form = document.getElementById('editForm');
    form.action = BASE_URL + '/' + id;

    let html = buildFormFields('edit', record);
    document.getElementById('editFormBody').innerHTML = html;

    prefillCascades('edit', record);

    openModal('editModal');
}

function buildFormFields(formId, record) {
    let html = '';

    // Parent dropdowns
    PARENT_CONFIG.forEach(parent => {
        const isDependent = !!parent.depends_on;
        html += `
        <div class="form-group">
            <label class="form-label">${parent.label} ${parent.required ? '<span style="color:var(--danger)">*</span>' : ''}</label>
            <select name="${parent.key}" id="${formId}_${parent.key}"
                class="form-control"
                ${parent.required ? 'required' : ''}
                ${isDependent
                    ? `data-depends="${parent.depends_on}" disabled`
                    : `data-cascade-for="${parent.cascade_for || ''}" data-cascade-url="${AJAX_BASE}/${parent.model}"`
                }
                onchange="handleParentChange(this, '${formId}')">
                <option value="">— Select ${parent.label} —</option>
            </select>
        </div>`;
    });

    // Primary name field
    if (PRIMARY_FIELD === 'name') {
        const val = record ? (record.name || '') : '';
        html += `
        <div class="form-group">
            <label class="form-label">Name <span style="color:var(--danger)">*</span></label>
            <input type="text" name="name" class="form-control" required
                placeholder="Enter name…" value="${escHtml(val)}">
        </div>`;
    }

    // Extra fields
    EXTRA_FIELDS.forEach(field => {
        if (field.key === PRIMARY_FIELD) return;
        const val = record ? (record[field.key] || field.default || '') : (field.default || '');
        html += `
        <div class="form-group">
            <label class="form-label">${field.label} ${field.required ? '<span style="color:var(--danger)">*</span>' : ''}</label>
            <input type="${field.type || 'text'}" name="${field.key}"
                class="form-control"
                placeholder="${field.label}…"
                value="${escHtml(String(val))}"
                ${field.required ? 'required' : ''}>
        </div>`;
    });

    // Income range — label + min_amount / max_amount / currency
    // FIX: was record.min_value / record.max_value — now record.min_amount / record.max_amount
    if (PRIMARY_FIELD === 'label') {
        const labelVal   = record ? (record.label || '') : '';
        const minVal     = record ? (record.min_amount ?? 0) : 0;
        const maxVal     = record ? (record.max_amount ?? '') : '';
        const currencies = ['INR','USD','GBP','EUR','AED','CAD','AUD'];
        const curVal     = record ? (record.currency || 'INR') : 'INR';
        const curOptions = currencies.map(c =>
            `<option value="${c}" ${curVal === c ? 'selected' : ''}>${c}</option>`
        ).join('');
        html += `
        <div class="form-group">
            <label class="form-label">Label <span style="color:var(--danger)">*</span></label>
            <input type="text" name="label" class="form-control" required
                placeholder="e.g. ₹5L – ₹10L per annum" value="${escHtml(labelVal)}">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
                <label class="form-label">Min Amount (₹) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="min_amount" class="form-control" required min="0"
                    placeholder="0" value="${minVal}">
            </div>
            <div class="form-group">
                <label class="form-label">Max Amount (₹)</label>
                <input type="number" name="max_amount" class="form-control" min="0"
                    placeholder="Leave blank for unlimited" value="${maxVal}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Currency</label>
            <select name="currency" class="form-control">${curOptions}</select>
        </div>`;
    }

    // Sort order
    const hasSortOrder = {{ in_array('sort_order', $columns) ? 'true' : 'false' }};
    if (hasSortOrder) {
        const val = record ? (record.sort_order ?? 0) : 0;
        html += `
        <div class="form-group">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" min="0"
                value="${val}" placeholder="0">
        </div>`;
    }

    // is_active toggle
    const isActive = record ? !!record.is_active : true;
    html += `
    <div class="form-group">
        <label class="form-label">Status</label>
        <div class="toggle-row">
            <label class="toggle-switch">
                <input type="hidden" name="is_active" value="${isActive ? '1' : '0'}">
                <input type="checkbox" name="is_active" value="1" ${isActive ? 'checked' : ''}
                    onchange="this.previousElementSibling.value = this.checked ? '1' : '0';
                              this.closest('.toggle-row').querySelector('.toggle-label').textContent = this.checked ? 'Active' : 'Inactive';">
                <span class="toggle-slider"></span>
            </label>
            <span class="toggle-label">${isActive ? 'Active' : 'Inactive'}</span>
        </div>
    </div>`;

    return html;
}

// ── CASCADE HANDLING ──────────────────────────────────────────
function handleParentChange(select, formId) {
    const cascadeFor = select.dataset.cascadeFor;
    if (!cascadeFor) return;

    const childId = `${formId}_${cascadeFor}`;
    const child   = document.getElementById(childId);
    if (!child) return;

    const parentVal = select.value;
    if (!parentVal) {
        child.innerHTML = '<option value="">— select parent first —</option>';
        child.disabled = true;
        return;
    }

    const childConfig = PARENT_CONFIG.find(p => p.key === cascadeFor);
    if (!childConfig) return;

    const fetchUrl = `${AJAX_BASE}/${childConfig.model}?parent_id=${parentVal}`;

    child.innerHTML = '<option value="">Loading…</option>';
    child.disabled = true;

    fetch(fetchUrl)
        .then(r => r.json())
        .then(data => {
            child.innerHTML = '<option value="">— Select —</option>' +
                data.map(item => `<option value="${item.id}">${item.name}</option>`).join('');
            child.disabled = false;
        })
        .catch(() => {
            child.innerHTML = '<option value="">Error loading…</option>';
        });
}

async function prefillCascades(formId, record) {
    for (const parent of PARENT_CONFIG) {
        if (parent.depends_on) continue;

        const sel = document.getElementById(`${formId}_${parent.key}`);
        if (!sel) continue;

        try {
            const res  = await fetch(`${AJAX_BASE}/${parent.model}`);
            const data = await res.json();
            sel.innerHTML = '<option value="">— Select —</option>' +
                data.map(item => `<option value="${item.id}" ${record && record[parent.key] == item.id ? 'selected' : ''}>${item.name}</option>`).join('');

            if (parent.cascade_for && record && record[parent.key]) {
                const cascadeFor  = parent.cascade_for;
                const childConfig = PARENT_CONFIG.find(p => p.key === cascadeFor);
                const child       = document.getElementById(`${formId}_${cascadeFor}`);

                if (childConfig && child) {
                    const fetchUrl = `${AJAX_BASE}/${childConfig.model}?parent_id=${record[parent.key]}`;
                    child.innerHTML = '<option value="">Loading…</option>';
                    child.disabled = true;

                    const childRes  = await fetch(fetchUrl);
                    const childData = await childRes.json();
                    child.innerHTML = '<option value="">— Select —</option>' +
                        childData.map(item => `<option value="${item.id}" ${record[cascadeFor] == item.id ? 'selected' : ''}>${item.name}</option>`).join('');
                    child.disabled = false;
                }
            }
        } catch(e) {
            console.error('Prefill error', e);
        }
    }
}

// ── DELETE CONFIRM ────────────────────────────────────────────
function confirmDelete(id, name) {
    document.getElementById('deleteModalMsg').textContent = `Delete "${name}"?`;
    document.getElementById('deleteForm').action = BASE_URL + '/' + id;
    openModal('deleteModal');
}

// ── TOGGLE STATUS ─────────────────────────────────────────────
function toggleStatus(id, currentState) {
    if (!confirm('Toggle status for this record?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = BASE_URL + '/' + id;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="is_active" value="${currentState ? '0' : '1'}">
        <input type="hidden" name="_toggle_only" value="1">
    `;
    document.body.appendChild(form);
    form.submit();
}

// ── TABLE SEARCH ──────────────────────────────────────────────
function filterTable(q) {
    const rows = document.querySelectorAll('#lookupTable tbody .record-row');
    const query = q.toLowerCase().trim();
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = (!query || text.includes(query)) ? '' : 'none';
    });
}

// ── CSV EXPORT ────────────────────────────────────────────────
function exportCSV() {
    const table = document.getElementById('lookupTable');
    const rows = [...table.querySelectorAll('tr')];
    const csv = rows.map(row =>
        [...row.querySelectorAll('th,td')]
            .slice(0, -1)
            .map(cell => `"${cell.textContent.trim().replace(/"/g,'""')}"`)
            .join(',')
    ).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href = url; a.download = `${LOOKUP_TYPE}.csv`; a.click();
    URL.revokeObjectURL(url);
}

// ── UTILITY ───────────────────────────────────────────────────
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

// ── INIT ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    PARENT_CONFIG.filter(p => !p.depends_on).forEach(parent => {
        const sel = document.getElementById(`create_${parent.key}`);
        if (!sel) return;
        fetch(`${AJAX_BASE}/${parent.model}`)
            .then(r => r.json())
            .then(data => {
                sel.innerHTML = `<option value="">— Select ${parent.label} —</option>` +
                    data.map(item => `<option value="${item.id}">${item.name}</option>`).join('');
            });
    });

    document.addEventListener('change', e => {
        if (e.target.type === 'checkbox' && e.target.name === 'is_active') {
            const label = e.target.closest('.toggle-row')?.querySelector('.toggle-label');
            if (label) label.textContent = e.target.checked ? 'Active' : 'Inactive';
        }
    });

    setTimeout(() => {
        document.getElementById('sessionAlert')?.remove();
    }, 4000);
});
</script>

<style>
.toggle-row {
    display: flex; align-items: center; gap: .875rem;
    padding: .5rem 0;
}
.toggle-switch {
    position: relative; display: flex; align-items: center; cursor: pointer;
}
.toggle-switch input[type="hidden"] { display: none; }
.toggle-switch input[type="checkbox"] { display: none; }
.toggle-slider {
    width: 44px; height: 24px; border-radius: 12px;
    background: var(--border); position: relative;
    transition: background .25s ease; flex-shrink: 0;
}
.toggle-slider::after {
    content: ''; position: absolute;
    top: 3px; left: 3px;
    width: 18px; height: 18px; border-radius: 50%;
    background: #fff; transition: transform .25s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,.2);
}
.toggle-switch input[type="checkbox"]:checked + .toggle-slider { background: var(--success); }
.toggle-switch input[type="checkbox"]:checked + .toggle-slider::after { transform: translateX(20px); }
.toggle-label { font-size: .82rem; color: var(--text-secondary); font-weight: 500; }
</style>

@endsection