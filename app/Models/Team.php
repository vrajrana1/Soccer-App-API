<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['name', 'logo'];
    
    /**
     * Get the players that belong to the team.
    **/
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
