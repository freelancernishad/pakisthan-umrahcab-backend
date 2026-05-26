<?php

namespace App\Models\Plan;


use App\Models\Plan\PlanFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'original_price',
        'discounted_price',
        'monthly_price',
        'discount_percentage',
        'features',  // stored as JSON
    ];

    protected $casts = [
        'features' => 'array',
    ];

    // Add this property to auto-append formatted_features to JSON output
    protected $appends = ['formatted_features'];
    protected $hidden = ['features'];

    protected static function booted()
    {
        static::creating(function ($plan) {
            self::calculatePrices($plan);
        });

        static::updating(function ($plan) {
            self::calculatePrices($plan);
        });
    }

    protected static function calculatePrices($plan)
    {
        $numericDuration = self::parseDuration($plan->duration);

        if (is_null($plan->original_price) && is_numeric($plan->monthly_price) && is_numeric($numericDuration)) {
            $plan->original_price = round($plan->monthly_price * $numericDuration, 2);
        }

        if (is_null($plan->monthly_price) && is_numeric($plan->original_price) && is_numeric($numericDuration) && $numericDuration > 0) {
            $plan->monthly_price = round($plan->original_price / $numericDuration, 2);
        }

        if (is_numeric($plan->original_price) && is_numeric($plan->discount_percentage)) {
            $plan->discounted_price = round($plan->original_price * (1 - $plan->discount_percentage / 100), 2);
        }

        if (!is_numeric($plan->discounted_price)) {
            $plan->discounted_price = 0;
        }

        $plan->duration = is_numeric($numericDuration) ? ($numericDuration == 1 ? "1 month" : "{$numericDuration} months") : $plan->duration;
    }

    protected static function parseDuration($duration)
    {
        if (is_numeric($duration)) {
            return (int) $duration;
        }

        if (is_string($duration) && preg_match('/(\d+)/', $duration, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * Get formatted features with rendered titles
     * @return array
     */
    public function getFormattedFeaturesAttribute()
    {
        if (empty($this->features) || !is_array($this->features)) {
            return [];
        }

        return collect($this->features)->map(function ($item) {
            $feature = PlanFeature::where('key', $item['key'])->first();

            if (!$feature) {
                // fallback: just return value or empty string
                return $item['value'] ?? '';
            }

            // Pass all other keys except 'key' as replacement data
            $data = $item;
            unset($data['key']);

            return $feature->render($data);
        })->filter()->all();
    }
}
