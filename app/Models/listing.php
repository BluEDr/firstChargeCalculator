<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class listing extends Model
{
    use HasFactory;
    public static function kano() {
        return [
        [    'heading' => 'Latest Listing',
            'title' => 'titlos tade',
            'description' => 'Reason'
        ],
        [    'heading' => 'wwwww Listing',
            'title' => 'titlos wwwwww',
            'description' => 'Reasonwwwwwwwwwwww'
        ]];
    }

    public static function incoming($id) {
        // $a = self::all();
        $a = self::kano();
        foreach($a as $aaa) {
            if ($aaa['description'] == $id) 
                return $aaa['description'];
        }
        return 1;

    }
}
