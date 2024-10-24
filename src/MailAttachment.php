<?php



namespace Solenoid\SMTP;



class MailAttachment
{
    public string       $content;
    public string          $name;
    public string      $encoding;
    public string          $type;
    public string   $disposition;



    # Returns [self]
    public function __construct
    (
        string $content,
        string $name        = '',
        string $encoding    = 'base64',
        string $type        = '',
        string $disposition = 'attachment'
    )
    {
        // (Getting the values)
        $this->content     = $content;
        $this->name        = $name;
        $this->encoding    = $encoding;
        $this->type        = $type;
        $this->disposition = $disposition;
    }
}



?>