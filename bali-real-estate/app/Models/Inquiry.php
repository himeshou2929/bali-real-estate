<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'property_id','user_id','agent_id',
        'type','subject','message','contact_method','preferred_date',
        'name','email','phone', // 既存フォーム入力
        'status','agent_reply','replied_at'
    ];

    protected $casts = [
        'preferred_date' => 'datetime',
        'replied_at'     => 'datetime',
    ];

    public function property() { return $this->belongsTo(\App\Models\Property::class); }
    public function user()     { return $this->belongsTo(\App\Models\User::class); }
    public function assignedUser() { return $this->belongsTo(\App\Models\User::class, 'agent_id'); }
}