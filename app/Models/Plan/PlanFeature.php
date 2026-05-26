<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    protected $fillable = [
        'key',
        'title_template',  // e.g. "View upto :value Contact Numbers"
        'unit',            // optional
    ];

    /**
     * Render the title_template by replacing placeholders with actual values
     * @param array $data key-value pairs for placeholders
     * @return string
     */
    public function render(array $data = []): string
    {
        $title = $this->title_template;

        foreach ($data as $key => $value) {
            $title = str_replace(":$key", $value, $title);
        }

        return $title;
    }
}
