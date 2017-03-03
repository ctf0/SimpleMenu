<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BaseModel extends Model
{
    use HasTranslations;

    protected $guarded = ['id'];
}
