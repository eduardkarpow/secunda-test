<?php

namespace App\Rules;

use App\Models\Activity;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use function PHPUnit\Framework\isNull;

class MaxTreeDepth implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!isNull($value)) {
            $parent = Activity::where('id', (int) $value);
            if ($parent->depth === 3) {
                $fail('The :attribute cannot be greater than 3.');
            }
        }
    }
}
