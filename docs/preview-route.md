# Creating a Preview Route

To display your form data in a preview page, you need to create a route and controller method that receives and renders the preview data.

## Step 1: Add the Route

In `routes/web.php`:

```php
Route::get('preview/{user}', [YourController::class, 'preview'])->name('your-preview-route');
```

**Note:** The route name should match the `previewRouteName` you pass to the PreviewField component.

## Step 2: Create the Controller Method

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

### What's Happening Here?

1. **Data Decoding**: The PreviewField sends form data as URL-encoded JSON via the `data` query parameter
2. **Security Check**: Validates that users can only preview their own data (optional but recommended)
3. **View Rendering**: Passes the decoded data to your Blade view

## Step 3: Create Your Preview View

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

### Preview-Specific Styling

You can pass an `is_preview` flag to apply special styling for the preview iframe:

**In your controller:**
```php
return view('your-preview-view', array_merge($data, [
    'is_preview' => true
]));
```

**In your view:**
```blade
@if($is_preview ?? false)
    <section class="h-[65rem] overflow-auto">
        {{-- Preview-specific layout --}}
    </section>
@else
    <section class="flex-1">
        {{-- Normal layout --}}
    </section>
@endif
```

## Complete Example

### Route
```php
Route::get('preview/artist/{user}', [GalleryController::class, 'preview'])
    ->name('gallery.preview');
```

### Controller
```php
public function preview(User $user)
{
    $data = json_decode(urldecode(request('data')), true) ?? [];

    // Security: ensure user can only preview their own data
    if ($user->username != $data['username'] || $user->username != Auth::user()?->username) {
        abort(404);
    }

    // Process any relational data (if needed)
    if (isset($data['galleries'])) {
        $data['galleries'] = Gallery::whereIn('id', $data['galleries'])->get();
    }

    return view('gallery.preview', $data);
}
```

### Blade View
```blade
<x-your-layout>
    <div class="container">
        <h1>{{ $company_name }}</h1>
        <p>{{ $description }}</p>

        @if($email)
            <a href="mailto:{{ $email }}">{{ $email }}</a>
        @endif

        @if($phone_number)
            <a href="tel:{{ $phone_number }}">{{ $phone_number }}</a>
        @endif
    </div>
</x-your-layout>
```

## Data Flow

```
Filament Form (with ->live())
    ↓
PreviewField collects form data
    ↓
Encodes data as URL-encoded JSON
    ↓
Sends to preview route via iframe
    ↓
Controller decodes and validates data
    ↓
Blade view renders preview
    ↓
User sees live preview in iframe
```
