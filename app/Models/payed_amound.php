<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payed_amound extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the payed_amound
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id'); //ayto xreiazete gia na deiloso to kseno kleidi ston pinaka users
    }

    public function category() {
        return $this->belongsTo(category::class, 'category_id', 'id');
    }
}
