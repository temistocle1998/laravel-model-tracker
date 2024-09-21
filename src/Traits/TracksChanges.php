<?php
namespace Tracker\Traits;

use Illuminate\Support\Facades\Auth;
use Tracker\Models\ModelChange;

trait TracksChanges
{
    // Automatically set created_by and updated_by fields and track changes
    protected static function bootTracksChanges()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();

                // Track changes
                $changes = $model->getDirty(); // Get the changed fields
                $original = $model->getOriginal(); // Get the original values

                if (!empty($changes)) {
                    $modelChanges = [];

                    foreach ($changes as $field => $newValue) {
                        $modelChanges[$field] = [
                            'old_value' => $original[$field] ?? null,
                            'new_value' => $newValue,
                        ];
                    }

                    ModelChange::create([
                        'model_type' => get_class($model),
                        'model_id'   => $model->id,
                        'user_id'    => Auth::id(),
                        'changes'    => json_encode($modelChanges),
                    ]);
                }
            }
        });
    }
}
