@php
    $icon = $getIcon($state);
@endphp

<div class="info_list">
    <label class="info_list_label font-bold text-primary-600">
        @if ($icon)
            @if ($setFontawesome ?? false)
                {{-- FontAwesome icon --}}
                <x-icon :name="$icon" set="fontawesome" class="fi-ta-text-item-icon h-5 w-5"/>
            @else
                {{-- Filament default icon --}}
                <x-filament::icon :icon="$icon" class="fi-ta-text-item-icon h-5 w-5"/>
            @endif
        @endif

        {{ $getLabel() }}
    </label>

    <p>{!! $state !!}</p>
</div>
