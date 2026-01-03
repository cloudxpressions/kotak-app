<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationValue extends Model
{
    protected $fillable = [
        'translation_key_id',
        'language_id',
        'value',
        'is_auto_translated',
        'last_updated_by'
    ];

    public function key()
    {
        return $this->belongsTo(TranslationKey::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
