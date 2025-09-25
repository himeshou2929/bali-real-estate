<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InquirySend extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id','user_id','to_email','subject','body',
        'template_key','mailable_class','status','message_id','error','meta','sent_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'sent_at' => 'datetime',
    ];

    public function inquiry(){ return $this->belongsTo(\App\Models\Inquiry::class); }
    public function user(){ return $this->belongsTo(\App\Models\User::class); }
}
