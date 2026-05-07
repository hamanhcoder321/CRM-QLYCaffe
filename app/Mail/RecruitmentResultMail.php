<?php

namespace App\Mail;

use App\Models\ListRecruitment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecruitmentResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(ListRecruitment $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        $statusText = $this->application->status == 1 ? 'ĐẠT' : 'KHÔNG ĐẠT';
        
        return $this->subject('[M&T Cafe] Thông báo kết quả ứng tuyển - ' . $statusText)
                    ->view('emails.recruitment_result');
    }
}
