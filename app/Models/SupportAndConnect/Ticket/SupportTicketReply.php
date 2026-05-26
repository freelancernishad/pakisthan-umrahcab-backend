<?php

namespace App\Models\SupportAndConnect\Ticket;

use App\Models\Admin;
use App\Models\User;
use App\Services\FileSystem\FileUploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportTicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'admin_id',
        'user_id',
        'reply',
        'reply_id',
        'attachment', // Add this line
    ];

    protected $with = [
        'admin',
        'user',
    ];


    // The parent reply relationship
    public function parent()
    {
        return $this->belongsTo(SupportTicketReply::class, 'reply_id');
    }

    // The children replies (nested replies)
    public function children()
    {
        return $this->hasMany(SupportTicketReply::class, 'reply_id');
    }

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class); // Assuming you have an Admin model
    }
    // Relationship with the User
    public function user()
    {
        return $this->belongsTo(User::class); // Assuming you have a User model
    }


    /**
     * Save the attachment for the reply.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string File path of the uploaded attachment
     */
    public function saveAttachment($file)
    {
        $filePath = (new FileUploadService())->uploadFileToS3($file, 'attachments/replies'); // Define the S3 directory
        $this->attachment = $filePath;
        $this->save();

        return $filePath;
    }
}
