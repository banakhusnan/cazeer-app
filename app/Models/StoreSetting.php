<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = ['store_name', 'address', 'phone', 'logo_path', 'low_stock_threshold'];

    protected function casts(): array
    {
        return ['low_stock_threshold' => 'integer'];
    }
}
