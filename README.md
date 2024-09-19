Here's an example `README.md` for your Laravel "Tracker" package:

---

# Laravel Model Tracker

[![Latest Stable Version](https://poser.pugx.org/your-namespace/laravel-model-tracker/v/stable)](https://packagist.org/packages/your-namespace/laravel-model-tracker)
[![Total Downloads](https://poser.pugx.org/your-namespace/laravel-model-tracker/downloads)](https://packagist.org/packages/your-namespace/laravel-model-tracker)
[![License](https://poser.pugx.org/your-namespace/laravel-model-tracker/license)](https://packagist.org/packages/your-namespace/laravel-model-tracker)

A lightweight Laravel package to track changes in your models. Automatically logs all changes, including old values, new values, and the user responsible for the changes.

## Features

- Automatically tracks changes in any Eloquent model.
- Stores old and new values of updated fields.
- Logs the user who made the change (if authenticated).
- Provides easy integration with a simple trait.
- Configurable to exclude fields from tracking.
- Includes a migration to store logs in a `model_changes` table.

## Requirements

- Laravel 8.x or 9.x
- PHP 7.4 or higher

## Installation

1. **Install via Composer:**

   ```bash
   composer require temistocle1998/laravel-model-tracker
   ```

2. **Publish configuration and migration files:**

   ```bash
   php artisan vendor:publish --tag=tracker-config
   php artisan migrate
   ```

3. **Add the `TracksChanges` trait to your model:**

   In any model where you want to track changes, simply include the `TracksChanges` trait:

   ```php
   use YourNamespace\Tracker\Traits\TracksChanges;

   class Product extends Model
   {
       use TracksChanges;
   }
   ```

## Configuration

The package comes with a config file that can be customized to suit your needs. The configuration file can be found at `config/tracker.php`.

### Example Config:

```php
return [
    'enabled' => true, // Enable or disable the tracking
    'exclude_fields' => ['password', 'remember_token'], // Fields you don't want to track
];
```

## Usage

Once the package is set up, any changes made to the models using the `TracksChanges` trait will automatically be logged. The logs are stored in the `model_changes` table.

### Example of Change Logs

When a model is updated, the following log is created in the `model_changes` table:

| model_type           | model_id | user_id | changes                                                            | created_at          |
|----------------------|----------|---------|--------------------------------------------------------------------|---------------------|
| App\Models\Product    | 1        | 2       | {"name": {"old_value": "Old Name", "new_value": "New Name"}}        | 2023-01-01 12:00:00 |

### Retrieving Model Changes

You can fetch model changes using the `ModelChange` model.

```php
use YourNamespace\Tracker\Models\ModelChange;

// Get all changes for a specific model
$changes = ModelChange::where('model_type', 'App\Models\Product')
                      ->where('model_id', $productId)
                      ->get();

// Display the changes
foreach ($changes as $change) {
    echo $change->changes;
}
```

### Displaying Changes in Your View

You can also display the changes in your views:

```php
@foreach ($product->changes as $change)
    <p>Field: {{ $change['field'] }} | Old Value: {{ $change['old_value'] }} | New Value: {{ $change['new_value'] }}</p>
@endforeach
```

## Contributing

Feel free to submit a pull request if you'd like to contribute to this package. All contributions are welcome!

## License

This package is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
