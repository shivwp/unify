<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;

use App\Models\Mails;



class SendMail extends Mailable

{

    use Queueable, SerializesModels;



    /**

     * Create a new message instance.

     *

     * @return void

     */

    public $data;

    public function __construct( $user)

    {

         $this->data=$user;

    }







    /**

     * Build the message.

     *

     * @return $this

     */

    public function build()

    {
         return $this->from($this->data['from_email'],$this->data['name'])

        ->subject($this->data['subject'])

        ->markdown('mails.mail');

    }

}

