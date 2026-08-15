<?php

namespace App\Traits\Admin\Helper;

trait SmartSetFunctionTrait {
  protected bool $setTranslationsRelation = false;
  protected string $setInputName = 'name';
  protected bool $setTransMode = false;
  protected bool $setDes = true;
  protected bool $setDataRequired = true;
  protected array $setActiveLang = [];
  protected bool $setSeoRequired = false;
  protected bool $setEditor = false;
  protected bool $setSeo = true;
  protected bool $setSeoCounter = true;
  protected string $setNameLabel = '';
  protected string $setDesLabel = '';
  protected bool $setMarkdown = false;
  protected bool $setRichEditor = false;
  protected int $setTextAreaRow = 6;
  protected bool $setShortDescription = false;
  protected int $setEditorHeight = 200;
  protected int|string|null $setColumnSpanFull = null;
  protected bool $setSectionCollapsed = false;
  protected bool $setInputDisabled = false;

  public function initializeSmartSetFunction(): void {
    $this->setNameLabel = __('default/lang.construct.name');
    $this->setDesLabel = __('default/lang.construct.description');
    $this->setActiveLang = getProjectActiveLocales();
  }

  public function setInputName(?string $name): static {
    $this->setInputName = $name ?? 'name';
    return $this;
  }

  public function setTransMode(?bool $setTransMode): static {
    $this->setTransMode = $setTransMode ?? false;
    return $this;
  }

  public function setTranslationsRelation(?bool $value): static {
    $this->setTranslationsRelation = $value ?? false;
    return $this;
  }

  public function setDes(bool $setDes): static {
    $this->setDes = $setDes;
    return $this;
  }

  public function setMarkdown(bool $setMarkdown): static {
    $this->setMarkdown = $setMarkdown;
    return $this;
  }

  public function setRichEditor(bool $setRichEditor): static {
    $this->setRichEditor = $setRichEditor;
    return $this;
  }


  public function setEditor(bool $setEditor): static {
    $this->setEditor = $setEditor;
    return $this;
  }

  public function setSeo(bool $setSeo): static {
    $this->setSeo = $setSeo;
    return $this;
  }

  public function setSeoCounter(bool $value): static {
    $this->setSeoCounter = $value;
    return $this;
  }

  public function setSeoRequired(bool $setSeoRequired): static {
    $this->setSeoRequired = $setSeoRequired;
    return $this;
  }

  public function setDataRequired(bool $setDataRequired): static {
    $this->setDataRequired = $setDataRequired;
    return $this;
  }

  public function setShortDescription(bool $setShortDescription): static {
    $this->setShortDescription = $setShortDescription;
    return $this;
  }

  public function setNameLabel(?string $label = null): static {
    $this->setNameLabel = $label ?? __('default/lang.columns.name');
    return $this;
  }

  public function setDesLabel(?string $label = null): static {
    $this->setDesLabel = $label ?? __('default/lang.columns.description');
    return $this;
  }

  public function setTextAreaRow(?string $setTextAreaRow = null): static {
    $this->setTextAreaRow = $setTextAreaRow ?? 6;
    return $this;
  }

  public function setActiveLang($value): static {
    $this->setActiveLang = $value;
    return $this;
  }

  public function setEditorHeight(?int $value): static {
    $this->setEditorHeight = $value;
    return $this;
  }

  public function setColumnSpanFull($value): static {
    $this->setColumnSpanFull = $value;
    return $this;
  }

  public function setSectionCollapsed(?bool $value): static {
    $this->setSectionCollapsed = $value ?? false;
    return $this;
  }

  public function setInputDisabled(?bool $value): static {
    $this->setInputDisabled = $value ?? false;
    return $this;
  }
}
