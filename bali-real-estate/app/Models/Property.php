<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;
    protected $casts=["title_translations"=>"array","description_translations"=>"array","latitude"=>"float","longitude"=>"float"];
    protected $fillable = [
        'area_id','title','address','latitude','longitude','price_usd','currency','bedrooms','bathrooms',
        'land_sqm','building_sqm','description','is_published','agent_id',
        'featured_image','status','title_translations','description_translations',
        'monthly_rent','yearly_rent','leasehold_price','freehold_price','yield_percent','leasehold_years'
    ];

    protected $appends = ['main_image_url'];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function agent() { return $this->belongsTo(\App\Models\User::class, 'agent_id'); }

    public function getMainImageUrlAttribute(): ?string
    {
        // images が配列に変換されている場合の対応
        $images = $this->images;
        
        if (is_array($images)) {
            $firstImage = $images[0] ?? null;
            return $firstImage['url'] ?? null;
        }
        
        // 通常のコレクションの場合
        return optional($images->first())->url ?? null;
    }
}
