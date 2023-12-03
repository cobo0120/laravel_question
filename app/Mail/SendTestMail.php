<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SendTestMail extends Mailable
{
    use Queueable, SerializesModels;

   

    /**
     * Create a new message instance.
     *
     * @return void
     */


    public function __construct($post,$item)
    {
        $this->post = $post;
        $this->item = $item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
                ->subject('消耗品・備品の申請がされました')
                ->view('emails.applicant_message')
                ->text('emails.applicant_message')
                ->from('test@test.com')//ここに申請者の名前とメールアドレスが記載されるように設定

              
                ->with(['post'=>$this->post,'item'=>$this->item]);
               
            
                
    }
}
