<div class="text-sm text-gray-600">
    <div>Selected filter: {{ $filter->name ?? 'N/A' }}</div>
    <div>Base size: {{ $filter->width ?? '?' }} x {{ $filter->height ?? '?' }}</div>
    @if(($filter->sizes_count ?? 0) > 0)
        <div>Extra sizes: {{ $filter->sizes_count }}</div>
    @endif
</div>
