<?php



use \Solenoid\SMTP\Connection;
use \Solenoid\SMTP\Mail;
use \Solenoid\SMTP\MailBox;
use \Solenoid\SMTP\MailBody;
use \Solenoid\SMTP\MailAttachment;
use \Solenoid\SMTP\Retry;



// (Creating a Connection)
$connection = new Connection( 'localhost', 465, 'sender@domain.tld', 'pass', Connection::ENCRYPTION_STARTTLS );



// (Creating a Mail)
$mail = new Mail
(
    new MailBox( $credentials['username'], 'test' ),

    [
        new MailBox( 'receiver@domain.tld' )
    ],

    [],
    [],
    [],

    'MAILTEST',
    new MailBody
    (
        '',

        'This is a mail <b>test</b> !'
    ),

    [
        new MailAttachment( 'This is an attachment', 'file.txt' )
    ]
)
;

if ( !$connection->send( $mail, new Retry() ) )
{// (Unable to send the mail)
    // Printing the value
    echo 'Unable to send the mail';
}



?>