
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'item_id',
        'borrower_name',
        'quantity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'item_id' => 'integer',
            'quantity' => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
