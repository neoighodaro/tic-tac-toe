<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['state', 'history', 'unit'];

    public function getStateAttribute()
    {
        return json_decode($this->attributes['state'], true);
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = json_encode($value);
    }

    public function getHistoryAttribute()
    {
        return json_decode($this->attributes['history'], true);
    }

    public function setHistoryAttribute($value)
    {
        $this->attributes['history'] = json_encode($value);
    }
}
