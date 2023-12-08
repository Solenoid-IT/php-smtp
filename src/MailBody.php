<?php



namespace Solenoid\SMTP;



class MailBody
{
    public string $text;
    public string $html;



    # Returns [self]
    public function __construct
    (
        string $text = '',
        string $html = ''
    )
    {
        // (Getting the values)
        $this->text = $text;
        $this->html = $html;    
    }

    # Returns [MailBody]
    public static function create
    (
        string $text = '',
        string $html = ''
    )
    {
        // Returning the value
        return new MailBody
        (
            $text,
            $html
        )
        ;
    }



    # Returns [self]
    public function fill (array $kv_data)
    {
        foreach ($kv_data as $k => $v)
        {// Processing each entry
            foreach ([ 'text', 'html' ] as $vv)
            // (Getting the values)
            $this->{ $vv } = str_replace("{! $k !}", $v, $this->{ $vv });
        }



        // Returning the value
        return $this;
    }
}



?>