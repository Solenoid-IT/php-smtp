<?php



namespace Solenoid\SMTP;



class Error
{
    public int    $code;
    public string $name;
    public string $description;



    # Returns [self]
    public function __construct (int $code, string $name, string $description = '')
    {
        // (Getting the values)
        $this->code        = $code;
        $this->name        = $name;
        $this->description = $description;
    }



    # Returns [assoc]
    public function to_array ()
    {
        // Returning the value
        return get_object_vars( $this );
    }

    # Returns [string]
    public function __toString ()
    {
        // Returning the value
        return implode( ' :: ', $this->to_array() );
    }
}



?>