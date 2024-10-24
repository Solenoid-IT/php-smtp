<?php



namespace Solenoid\SMTP;



use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;



class Config
{
    private static $encryptions =
    [
        'SMTPS'    => PHPMailer::ENCRYPTION_SMTPS,
        'STARTTLS' => PHPMailer::ENCRYPTION_STARTTLS
    ]
    ;



    public array $properties;



    # Returns [self]
    public function __construct
    (
        string $host             = 'localhost',
        int    $port             = 465,

        string $username,
        string $password,

        string $encryption_type  = 'STARTTLS',

        string $charset_encoding = 'UTF-8',

        bool   $debug            = false
    )
    {
        // (Setting the value)
        $this->properties = [];



        // (Getting the values)
        $this->properties['Host']       = $host;
        $this->properties['Port']       = $port;//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $this->properties['SMTPAuth']   = true;

        $this->properties['Username']   = $username;
        $this->properties['Password']   = $password;

        $this->properties['SMTPSecure'] = self::$encryptions[ $encryption_type ];

        $this->properties['CharSet']    = $charset_encoding;



        if ( $debug )
        {// Value is true
            // (Setting the value)
            $this->properties['SMTPDebug'] = SMTP::DEBUG_SERVER;
        }
    }



    # Returns [PHPMailer]
    public function create_mailer ()
    {
        // (Creating a PHPMailer)
        $mailer = new PHPMailer( true );

        // (Setting the value)
        $mailer->isSMTP();



        foreach ( $this->properties as $k => $v )
        {// Processing each entry
            // (Getting the value)
            $mailer->{ $k } = $v;
        }



        // Returning the value
        return $mailer;
    }
}



?>