<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'name', 'category', 'unit', 'selling_price', 'stock', 'description', 'image_path', 'is_active'];

    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return ['selling_price' => 'integer', 'stock' => 'integer', 'is_active' => 'boolean'];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if (! $this->image_path) {
                return asset('assets/images/samples/300x300/1.jpg');
            }

            return str_starts_with($this->image_path, 'assets/')
                ? asset($this->image_path)
                : asset('storage/'.$this->image_path);
        });
    }
}
