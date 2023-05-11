<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['fname', 'lname', 'photo', 'team_id'];
    
    /**
     * Get the team that the player belongs to.
     */
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
