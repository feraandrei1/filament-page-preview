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
                'previewRouteName' => 'preview',
                'data' => $data,
            ])
            ->nullable(),
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

## Creating a Preview Route

To display your form data in a preview page, you need to create a route and controller method.

### Step 1: Add the Route

In `routes/web.php`:

```php
Route::get('preview/{user}', [YourController::class, 'preview'])->name('your-preview-route');
```

### Step 2: Create the Controller Method

```php
public function preview(User $user)
{
    // Decode the preview data from URL
    $data = json_decode(urldecode(request('data')), true) ?? [];

    // Optional: Add security check
    if ($user->username != $data['username']) {
        abort(404);
    }

    // Return your preview view with the data
    return view('your-preview-view', $data);
}
```

### Step 3: Create Your Preview View

Create a simple Blade view that displays the preview data:

```blade
{{-- resources/views/your-preview-view.blade.php --}}

<div>
    <h1>{{ $company_name ?? 'Company Name' }}</h1>
    <p>{{ $description ?? '' }}</p>

    @if(isset($galleries))
        @foreach($galleries as $gallery)
            <div>{{ $gallery->name }}</div>
        @endforeach
    @endif
</div>
```

### How It Works

1. The `PreviewField` sends form data as URL-encoded JSON via the `data` parameter
2. Your controller decodes this data and passes it to your view
3. Your view renders the preview using the provided data

**Tip:** Use the `is_preview` variable to apply special styling for the preview iframe.

## Requirements

- PHP 8.1 or higher
- Filament 3.0 or higher
- Laravel 10.0 or higher

## License

MIT License
