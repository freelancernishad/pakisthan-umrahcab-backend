<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcChatMessage extends Model
{
    use HasFactory;

    protected $table = 'uc_chat_messages';

    protected $fillable = [
        'company_id',
        'sender_type',
        'sender_id',
        'message',
        'attachment',
        'reply_to_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the company associated with the chat message.
     */
    public function company()
    {
        return $this->belongsTo(UcCompany::class, 'company_id');
    }

    /**
     * Get the parent message that this message is replying to.
     */
    public function replyTo()
    {
        return $this->belongsTo(UcChatMessage::class, 'reply_to_id')->with('replyTo');
    }

    /**
     * Get the child replies of this message.
     */
    public function replies()
    {
        return $this->hasMany(UcChatMessage::class, 'reply_to_id');
    }
}
