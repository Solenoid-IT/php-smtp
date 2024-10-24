<?php



namespace Solenoid\SMTP;



use \Solenoid\SMTP\Config;
use \Solenoid\SMTP\Error;
use \Solenoid\SMTP\Retry;



class Connection
{
    const ENCRYPTION_SMTPS    = 'SMTPS';
    const ENCRYPTION_STARTTLS = 'STARTTLS';



    private array  $errors;
    private Config $config;



    # Returns [Error|false]
    private function find_error (string $error_info)
    {
        foreach ($this->errors as $k => $v)
        {// Processing each entry
            if ( stripos( $error_info, $k ) !== false )
            {// Value found
                // Returning the value
                return $v;
            }
        }



        // Returning the value
        return false;
    }



    # Returns [self]
    public function __construct
    (
        string $host             = 'localhost',
        int    $port             = 465,

        string $username,
        string $password,

        string $encryption_type  = self::ENCRYPTION_STARTTLS,

        string $charset_encoding = 'UTF-8',

        bool   $debug            = false
    )
    {
        // (Getting the values)
        $this->errors        =
        [
            'You must provide at least one recipient email address.' => Error::create
            (
                0,
                'NO_RECEIVER',
                'Receiver list is empty'
            ),

            ' mailer is not supported.'                              => Error::create
            (
                1,
                'MAILER_NOT_SUPPORTED',
                'Mailer is not supported'
            ),

            'Could not execute: '                                    => Error::create
            (
                2,
                'EXECUTION_FAILED',
                'Execution is failed'
            ),

            'Could not instantiate mail function.'                   => Error::create
            (
                3,
                'MF_INSTANTIATION_FAILED',
                'Unable to instantiate mail function'
            ),

            'SMTP Error: Could not authenticate.'                    => Error::create
            (
                4,
                'AUTH_FAILED',
                'Authentication is failed'
            ),

            'The following From address failed: '                    => Error::create
            (
                5,
                'FROM_FAILED',
                'From is not valid'
            ),

            'SMTP Error: The following recipients failed: '          => Error::create
            (
                6,
                'RECEIVERS_FAILED',
                'Unable to send the mail to the receivers'
            ),

            'SMTP Error: Data not accepted.'                         => Error::create
            (
                7,
                'DATA_NOT_ACCEPTED',
                'Data is not accepted'
            ),

            'SMTP Error: Could not connect to SMTP host.'            => Error::create
            (
                8,
                'SERVER_UNREACHABLE',
                'Unable to reach the server'
            ),

            'Could not access file: '                                => Error::create
            (
                9,
                'FILE_ACCESS_FAILED',
                'Unable to access to the file'
            ),

            'File Error: Could not open file: '                      => Error::create
            (
                10,
                'FILE_OPEN_FAILED',
                'Unable to open the file'
            ),

            'Unknown encoding: '                                     => Error::create
            (
                11,
                'ENCODING_NOT_RECOGNIZED',
                'Encoding not recognized'
            ),

            'Signing Error: '                                        => Error::create
            (
                12,
                'SIGNING_FAILED',
                'Unable to sign'
            ),

            'SMTP server error: '                                    => Error::create
            (
                13,
                'SERVER_ERROR',
                'Server error'
            ),

            'Message body empty'                                     => Error::create
            (
                14,
                'EMPTY_BODY',
                'Mail body is empty'
            ),

            'Invalid address'                                        => Error::create
            (
                15,
                'INVALID_ADDRESS',
                'Address is not valid'
            ),

            'Cannot set or reset variable: '                         => Error::create
            (
                16,
                'VARIABLE_SET_FAILED',
                'Unable set the variable'
            )
        ]
        ;

        $this->config = new Config
        (
            $host,
            $port,

            $username,
            $password,

            $encryption_type,

            $charset_encoding,

            $debug
        )
        ;
    }



    # Returns [bool] | Throws [Exception]
    public function send (Mail $mail, ?Retry $retry = null, ?Error &$error = null)
    {
        // (Getting the value)
        $php_mailer = $this->config->create_mailer();



        // (Setting the value)
        $php_mailer->setFrom( $mail->sender->email, $mail->sender->name );

        foreach ($mail->receiver_list as $entry)
        {// Processing each entry
            // (Adding an address)
            $php_mailer->addAddress( $entry->email, $entry->name );
        }



        foreach ($mail->cc_list as $entry)
        {// Processing each entry
            // (Adding a CC)
            $php_mailer->addCC( $entry->email, $entry->name );
        }

        foreach ($mail->bcc_list as $entry)
        {// Processing each entry
            // (Adding a CC)
            $php_mailer->addBCC( $entry->email, $entry->name );
        }



        foreach ($mail->reply_to_list as $entry)
        {// Processing each entry
            // (Adding a ReplyTo)
            $php_mailer->addReplyTo( $entry->email, $entry->name );
        }



        // (Setting the values)
        $php_mailer->isHTML( $mail->body->html ? true : false );

        $php_mailer->Subject = $mail->subject;

        $php_mailer->Body    = $mail->body->html;
        $php_mailer->AltBody = $mail->body->text;



        foreach ($mail->attachment_list as $entry)
        {// Processing each entry
            // (Adding an attachment)
            $php_mailer->addStringAttachment
            (
                $entry->content,
                $entry->name,
                $entry->encoding,
                $entry->type,
                $entry->disposition
            )
            ;
        }



        // (Getting the values)
        $num_attempts  = 1 + ( $retry ? $retry->num_attempts : 0 );
        $time_interval = $retry ? $retry->time_interval : 0;

        for ($i = 1; $i <= $num_attempts; $i++)
        {// Iterating each index
            try
            {
                // (Sending the mail)
                $result = $php_mailer->send();
            }
            catch (\Exception $e)
            {
                // (Getting the value)
                $error = $this->find_error( $php_mailer->ErrorInfo );

                if ( $error->name !== 'SERVER_UNREACHABLE' )
                {// (There is another error)
                    // Returning the value
                    return false;
                }
            }



            if ( $result )
            {// Value is true
                // Breaking the iteration
                break;
            }



            // (Waiting for the seconds)
            sleep( $time_interval );
        }



        if ( $error->name === 'SERVER_UNREACHABLE' )
        {// (Server is unreachable)
            // Returning the value
            return false;
        }



        // Returning the value
        return true;
    }
}



?>