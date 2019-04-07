<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'company_id',
        'messages',
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'messages',
    ];

    protected $casts = [
        'messages' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
