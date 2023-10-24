<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parts extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sellPrice',
        'purchasePrice',
        'admin_id',
        'quantity',
    ];


    /**
     * Get the admin that owns the Parts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

}
