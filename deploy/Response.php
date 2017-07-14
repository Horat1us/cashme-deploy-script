<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 6:50 PM
 */

namespace Horat1us\Deploy;


/**
 * Class Response
 * @package Horat1us\Deploy
 */
class Response
{

    /**
     * @var int
     */
    public $code = 200;

    /**
     * @var array|string
     */
    public $response;

    /**
     * Response constructor.
     * @param int $code
     * @param array|string $response
     */
    public function __construct($response, int $code)
    {
        $this->code = $code;
        $this->response = $response;
    }

    /**
     * @return void
     */
    public function push()
    {
        http_response_code($this->code);

        echo is_array($this->response)
            ? json_encode($this->response)
            : $this->response;
    }
}