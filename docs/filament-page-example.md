# Complete Filament Page Example

Here's a real-world example showing how to integrate the preview field with live form data in a Filament page.

## Example: Homepage Settings Page

```php
use Feraandrei1\FilamentPagePreview\Forms\Components\PreviewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;

class HomePageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.home-page-settings';

    // Your form fields
    public $company_name;
    public $description;
    public $email;
    public $phone_number;

    public function form(Form $form): Form
    {
        // Prepare data for preview
        $previewData = [
            'username' => Auth::user()->username,
            'company_name' => $this->company_name,
            'description' => $this->description,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
        ];

        return $form->schema([

            // Left column: Form fields
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Company Information')
                        ->schema([
                            Forms\Components\TextInput::make('company_name')
                                ->required()
                                ->live(), // Update preview on change

                            Forms\Components\Textarea::make('description')
                                ->live(),

                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->live(),

                            Forms\Components\TextInput::make('phone_number')
                                ->live(),
                        ]),
                ])
                ->columnSpan(1),

            // Right column: Live preview
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Preview')
                        ->schema([
                            PreviewField::make('preview_field')
                                ->previewData([
                                    'user' => Auth::user()->username,
                                    'previewRouteName' => 'gallery.preview', // Your route name
                                    'data' => $previewData,
                                ])
                                ->visible(fn() => !empty($this->company_name)),

                            Forms\Components\Placeholder::make('no_preview')
                                ->content('Fill in the company name to see a preview')
                                ->hidden(fn() => !empty($this->company_name)),
                        ]),
                ])
                ->columnSpan(1),

        ])->columns(2);
    }

    public function save(): void
    {
        // Save your data
        $this->validate();
        // Your save logic here...
    }
}
```

## Key Points

- **Live Updates**: Use `->live()` on form fields to update the preview in real-time as users type
- **Data Structure**: Pass your form data as an array to `previewData()` under the `'data'` key
- **Route Name**: Set `previewRouteName` to match your preview route name (see [Creating a Preview Route](preview-route.md))
- **Conditional Display**: Use `->visible()` to show/hide the preview based on whether required fields are filled
- **Two-Column Layout**: Form fields on the left, live preview on the right for better UX

## How It Works

1. User fills in form fields
2. Thanks to `->live()`, the form state updates immediately
3. The `$previewData` array is rebuilt with current values
4. PreviewField sends this data to your preview route via iframe
5. Your preview route renders the page with the provided data
6. User sees live preview without saving
