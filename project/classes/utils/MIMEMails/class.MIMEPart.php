<?php
class MIMEPart
{
    /**
    * The encoding type of this part
    * @var string
    */
    private $encoding;

    /**
    * An array of subparts
    * @var array
    */
    private $subparts;

    /**
    * The output of this part after being built
    * @var string
    */
    private $encoded;

    /**
    * Headers for this part
    * @var array
    */
    private $headers;

    /**
    * The body of this part (not encoded)
    * @var string
    */
    private $body;

    /**
    * Constructor.
    *
    * Sets up the object.
    *
    * @param $body   - The body of the mime part if any.
    * @param $params - An associative array of parameters:
    *                  content_type - The content type for this part eg multipart/mixed
    *                  encoding     - The encoding to use, 7bit, 8bit, base64, or quoted-printable
    *                  cid          - Content ID to apply
    *                  disposition  - Content disposition, inline or attachment
    *                  dfilename    - Optional filename parameter for content disposition
    *                  description  - Content description
    *                  charset      - Character set to use
    * @access public
    */
    public function __construct($body = '', $params = array())
    {
        if (!defined('MIMEPart_CRLF')) {
            define('MIMEPart_CRLF', defined('MAIL_MIME_CRLF') ? MAIL_MIME_CRLF : "\r\n", true);
        }

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'content_type':
                    $headers['Content-Type'] = $value . (isset($charset) ? '; charset="' . $charset . '"' : '');
                    break;

                case 'encoding':
                    $this->encoding = $value;
                    $headers['Content-Transfer-Encoding'] = $value;
                    break;

                case 'cid':
                    $headers['Content-ID'] = '<' . $value . '>';
                    break;

                case 'disposition':
                    $headers['Content-Disposition'] = $value . (isset($dfilename) ? '; filename="' . $dfilename . '"' : '');
                    break;

                case 'dfilename':
                    if (isset($headers['Content-Disposition'])) {
                        $headers['Content-Disposition'] .= '; filename="' . $value . '"';
                    } else {
                        $dfilename = $value;
                    }
                    break;

                case 'description':
                    $headers['Content-Description'] = $value;
                    break;

                case 'charset':
                    if (isset($headers['Content-Type'])) {
                        $headers['Content-Type'] .= '; charset="' . $value . '"';
                    } else {
                        $charset = $value;
                    }
                    break;
            }
        }

        // Default content-type
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'text/plain';
        }

        // Default encoding
        if (!isset($this->encoding)) {
            $this->encoding = '7bit';
        }

        // Assign stuff to member variables
        $this->encoded  = array();
        $this->headers  = $headers;
        $this->body     = $body;
    }

    /**
    * Encodes and returns the email. Also stores
    * it in the encoded member variable
    *
    * @return An associative array containing two elements,
    *         body and headers. The headers element is itself
    *         an indexed array.
    */
    public function encode()
    {
        $encoded =& $this->encoded;

        if (!empty($this->subparts)) {
            srand((double)microtime()*1000000);
            $boundary = '=_' . md5(uniqid(rand()) . microtime());
            $this->headers['Content-Type'] .= ';' . MIMEPart_CRLF . "\t" . 'boundary="' . $boundary . '"';

            // Add body parts to $subparts
            for ($i = 0; $i < count($this->subparts); $i++) {
                $headers = array();
                $tmp = $this->subparts[$i]->encode();
                foreach ($tmp['headers'] as $key => $value) {
                    $headers[] = $key . ': ' . $value;
                }
                $subparts[] = implode(MIMEPart_CRLF, $headers) . MIMEPart_CRLF . MIMEPart_CRLF . $tmp['body'];
            }

            $encoded['body'] = '--' . $boundary . MIMEPart_CRLF .
                               implode('--' . $boundary . MIMEPart_CRLF, $subparts) .
                               '--' . $boundary.'--' . MIMEPart_CRLF;
        } else {
            $encoded['body'] = $this->getEncodedData($this->body, $this->encoding) . MIMEPart_CRLF;
        }

        // Add headers to $encoded
        $encoded['headers'] =& $this->headers;

        return $encoded;
    }

    /**
    * Adds a subpart to current mime part and returns
    * a reference to it
    *
    * @param $body   The body of the subpart, if any.
    * @param $params The parameters for the subpart, same
    *                as the $params argument for constructor.
    * @return A reference to the part you just added.
    */
    public function addSubPart($body, $params)
    {
        $this->subparts[] = new MIMEPart($body, $params);
        
        return $this->subparts[count($this->subparts) - 1];
    }

    /**
    * Returns encoded data based upon encoding passed to it
    *
    * @param $data     The data to encode.
    * @param $encoding The encoding type to use, 7bit, base64,
    *                  or quoted-printable.
    */
    private function getEncodedData($data, $encoding)
    {
        switch ($encoding) {
            case '8bit':
            case '7bit':
                return $data;
                break;

            case 'quoted-printable':
                return $this->quotedPrintableEncode($data);
                break;

            case 'base64':
                return rtrim(chunk_split(base64_encode($data), 76, MIMEPart_CRLF));
                break;

            default:
                return $data;
        }
    }

    /**
    * Encodes data to quoted-printable standard.
    *
    * @param $input    The data to encode
    * @param $line_max Optional max line length. Should
    *                  not be more than 76 chars
    */
    private function quotedPrintableEncode($input , $line_max = 76)
    {
        $lines  = preg_split("/\r?\n/", $input);
        $eol    = MIMEPart_CRLF;
        $escape = '=';
        $output = '';

        while(list(, $line) = each($lines)){

            $linlen     = strlen($line);
            $newline = '';

            for ($i = 0; $i < $linlen; $i++) {
                $char = substr($line, $i, 1);
                $dec  = ord($char);

                if (($dec == 32) AND ($i == ($linlen - 1))){    // convert space at eol only
                    $char = '=20';

                } elseif($dec == 9) {
                    ; // Do nothing if a tab.
                } elseif(($dec == 61) OR ($dec < 32 ) OR ($dec > 126)) {
                    $char = $escape . strtoupper(sprintf('%02s', dechex($dec)));
                }

                if ((strlen($newline) + strlen($char)) >= $line_max) {        // MIMEPart_CRLF is not counted
                    $output  .= $newline . $escape . $eol;                    // soft line break; " =\r\n" is okay
                    $newline  = '';
                }
                $newline .= $char;
            } // end of for
            $output .= $newline . $eol;
        }
        $output = substr($output, 0, -1 * strlen($eol)); // Don't want last crlf
        return $output;
    }
} // End of class
?>