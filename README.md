# Filament Page Preview

Custom Filament form field that renders a Blade preview.

## Installation

Install the package via composer:

```bash
composer require ferarandrei1/filament-page-preview
```

## Usage

Use the `PreviewField` component in your Filament form:

```php
use Feraandrei1\FilamentPagePreview\Forms\Components\PreviewField;
use Filament\Forms\Form;

public function form(Form $form): Form
{
    return $form->schema([
        PreviewField::make('preview_field')
            ->previewData([
                'user' => Auth::user()->username,
                'data' => $data,
            ])
            ->nullable()
            ->visible(fn (): bool => $this->company_name ?? false),
    ]);
}
```

### Publishing Views

You can publish the views to customize them:

```bash
php artisan vendor:publish --tag=filament-page-preview-views
```

### Custom Preview View

You can specify a custom view for the preview:

```php
PreviewField::make('preview_field')
    ->previewView('filament.view-fields.custom-preview')
    ->previewData(['key' => 'value']);
```

## Requirements

- PHP 8.1 or higher
- Filament 3.0 or higher
- Laravel 10.0 or higher

## License

MIT License
