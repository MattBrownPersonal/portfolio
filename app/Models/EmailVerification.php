<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'email_hash',
        'verified',
    ];

    public static function getEmailVerification($email)
    {
        $emailVerification = self::where('email', $email)->first();

        return $emailVerification;
    }

    public static function verifyToken($token)
    {
        $emailVerification = self::where('email_hash', $token)->first();
        if (! $emailVerification) {
            throw new \Exception('Verification token could not be found:'.$token);
        }
        $emailVerification->verified = true;
        $emailVerification->save();

        return $emailVerification->verified;
    }

    public static function newEmailVerification($email)
    {
        $emailVerification = new self;
        $emailVerification->email = $email;
        $emailVerification->email_hash = hash('sha256', $email);
        // verified defaults to false
        $emailVerification->save();

        return $emailVerification;
    }
}
