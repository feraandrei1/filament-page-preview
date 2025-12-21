<?php

namespace Feraandrei1\FilamentPagePreview\Forms\Components;

use Filament\Forms\Components\ViewField;

class PreviewField extends ViewField
{
    protected string $view = 'filament-page-preview::filament.view-fields.preview-home-page';

    public static function make(string $name): static
    {
        return parent::make($name);
    }

    public function previewView(string $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function previewData(array $data): static
    {
        $this->viewData($data);

        return $this;
    }
}
