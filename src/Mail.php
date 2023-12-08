<?php



namespace Solenoid\SMTP;



use \Solenoid\SMTP\MailBox;
use \Solenoid\SMTP\MailBody;



class Mail
{
    public MailBox           $sender;

    public array      $receiver_list;

    public array            $cc_list;
    public array           $bcc_list;

    public array      $reply_to_list;

    public string           $subject;
    public MailBody            $body;
    public array    $attachment_list;



    # Returns [self]
    public function __construct
    (
        MailBox  $sender,

        array    $receiver_list,
        
        array    $cc_list         = [],
        array    $bcc_list        = [],

        array    $reply_to_list   = [],

        string   $subject,
        MailBody $body,
        array    $attachment_list = []
    )
    {
        // (Getting the values)
        $this->sender          = $sender;

        $this->receiver_list   = $receiver_list;

        $this->cc_list         = $cc_list;
        $this->bcc_list        = $bcc_list;

        $this->reply_to_list   = $reply_to_list;

        $this->subject         = $subject;
        $this->body            = $body;
        $this->attachment_list = $attachment_list;
    }

    # Returns [Mail]
    public static function create
    (
        MailBox  $sender,

        array    $receiver_list,
        
        array    $cc_list         = [],
        array    $bcc_list        = [],

        array    $reply_to_list   = [],

        string   $subject,
        MailBody $body,
        array    $attachment_list = []
    )
    {
        // Returning the value
        return new Mail
        (
            $sender,

            $receiver_list,

            $cc_list,
            $bcc_list,

            $reply_to_list,

            $subject,
            $body,
            $attachment_list
        )
        ;
    }
}



?>