<?php
namespace Tracker\Models;

use Illuminate\Database\Eloquent\Model;

class ModelChange extends Model
{
    protected $fillable = ['model_type', 'model_id', 'user_id', 'changes'];
    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
