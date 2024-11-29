<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueEmail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'to_email',
        'email_verification_id',
        'email_body',
        'email_subject',
        'email_json',
        'email_type',
        'deceased_id',
        'sent',
    ];

    public static function queueEmail($toEmail, $emailVerificationId, $emailBody, $emailSubject, $deceasedId, $emailType, $emailJson)
    {
        $queueEmail = new self;
        $queueEmail->to_email = $toEmail;
        $queueEmail->email_verification_id = $emailVerificationId;
        $queueEmail->email_body = $emailBody;
        $queueEmail->email_subject = $emailSubject;
        $queueEmail->email_json = $emailJson;
        $queueEmail->email_type = $emailType;
        $queueEmail->deceased_id = $deceasedId;
        // sent defaults to false
        $queueEmail->save();
    }

    public function EmailVerifications()
    {
        return $this->belongsToMany(EmailVerification::class);
    }

    public function Deceaseds()
    {
        return $this->belongsToMany(Deceased::class);
    }
}
