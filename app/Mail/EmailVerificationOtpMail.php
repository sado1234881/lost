<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public int $expiryMinutes;

    public function __construct(string $otpCode, int $expiryMinutes = 10)
    {
        $this->otpCode = $otpCode;
        $this->expiryMinutes = $expiryMinutes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SJUT Lost & Found — Email Verification Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: '
                <div style="font-family: \'Segoe UI\', Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 30px; background-color: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                    <div style="text-align: center; margin-bottom: 24px;">
                        <h2 style="color: #3b0764; font-size: 20px; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: 1px;">St. John\'s University of Tanzania</h2>
                        <p style="color: #6b21a8; font-size: 12px; font-weight: 700; margin-top: 4px; text-transform: uppercase;">Web-Based Lost Property Recovery System</p>
                    </div>

                    <div style="background-color: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                        <p style="color: #1e293b; font-size: 14px; margin-top: 0;">Hello,</p>
                        <p style="color: #334155; font-size: 14px; leading-height: 1.6;">Your verification code for <strong>SJUT Lost & Found</strong> is:</p>
                        
                        <div style="text-align: center; margin: 24px 0;">
                            <span style="display: inline-block; background-color: #3b0764; color: #fbbf24; font-size: 32px; font-weight: 800; letter-spacing: 8px; padding: 14px 28px; border-radius: 12px; border: 2px solid #f59e0b;">' . $this->otpCode . '</span>
                        </div>

                        <p style="color: #64748b; font-size: 13px;">This code will expire in <strong>' . $this->expiryMinutes . ' minutes</strong>. Do not share this code with anyone.</p>
                        <p style="color: #64748b; font-size: 13px;">If you did not request this code, you can safely ignore this email.</p>

                        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">
                        <p style="color: #94a3b8; font-size: 12px; margin: 0;">Regards,<br><strong style="color: #475569;">SJUT Lost & Found Security Team</strong></p>
                    </div>

                    <div style="text-align: center; margin-top: 20px; font-size: 11px; color: #94a3b8;">
                        <p>Dodoma, Tanzania • "To Learn To Serve"</p>
                    </div>
                </div>
            ',
        );
    }
}
