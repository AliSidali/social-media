<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TotalFilesSize implements ValidationRule
{

    protected $maxSize;
    public function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $filesSize = 0;
        foreach ($value as $file) {
            $filesSize += $file->getSize();
        }
        if ($this->maxSize < $filesSize) {
            $fail('the total size must be lower than 500MB');
        }
    }
}
