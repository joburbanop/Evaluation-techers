<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Institution extends Model
{
    use HasFactory;

    protected $table = 'institutions';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'contact_person',
        'foundation_date',
    ];

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'institution_test');
    }
}
