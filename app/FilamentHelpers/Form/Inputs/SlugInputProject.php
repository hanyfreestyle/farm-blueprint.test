<?php

namespace App\FilamentHelpers\Form\Inputs;


use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;


class SlugInputProject extends TextInput {
  protected ?string $locale = 'en';
  protected ?string $permission = "no-edit";
  protected ?string $columnSpanCol = "full";

  public static function make(string $name): static {
    $instance = parent::make($name);

    return $instance
      ->label(__('default/lang.columns.slug'))
      ->unique(ignoreRecord: true)
      // ✅ عرض ID كـ prefix ثابت في input
      ->prefix(fn ($livewire) => $livewire->record?->id ? $livewire->record->id . '-' : null)

      // ✅ عند تحميل النموذج: إزالة البادئة من القيمة المعروضة للمستخدم
      ->afterStateHydrated(function (TextInput $component, $state, $livewire) {
        $id = $livewire->record?->id;

        if ($id && Str::startsWith($state, $id . '-')) {
          $component->state(Str::after($state, $id . '-'));
        }
      })

      // ✅ عند تحديث الحقل (أثناء الكتابة): نعمل slugify
      ->afterStateUpdated(function ($state, callable $set) use ($name) {
        $slug = Url_Slug($state);
        $set($name, $slug);
      })

      // ✅ قبل الحفظ: نضيف ID كبادئة + slugify
      ->beforeStateDehydrated(function ($state, $livewire) {
        $id = $livewire->record?->id;

        return $id ? $id . '-' . Url_Slug($state) : Url_Slug($state);
      })
      ->required();
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  protected function setUp(): void {
    parent::setUp();
    $this->extraAttributes(fn () => rtlIfArabic($this->locale));
    $this->disabled(fn ($record) => $this->handlePermission($record));

  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function setColumnSpan(string $columnSpan): static {
    $this->columnSpanCol = $columnSpan;
    return $this;
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function setLocale(string $locale): static {
    $this->locale = $locale;
    return $this;
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function setPermission(string $permission): static {
    $this->permission = $permission;
    return $this;
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function handlePermission($record): bool {

    if (!$record) {
      return false;
    }
    if (Auth::user()->id == 1) {
      return false;
    }
    if (Gate::allows('updateSlug', $record)) {
      return false;
    }
    return true;
  }

}

