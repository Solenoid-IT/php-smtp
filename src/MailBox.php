<?php



namespace Solenoid\SMTP;



class MailBox
{
    public string $email;
    public string  $name;



    # Returns [self]
    public function __construct (string $email, string $name = '')
    {
        // (Getting the values)
        $this->email = $email;
        $this->name  = $name;
    }
}



?>