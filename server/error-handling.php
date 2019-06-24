<?php

class ApiException extends Exception {
    private $output;

    public function __construct($output, $code = 500, $message = 'Internal Server Error', Exception $prev = null){
        $this->output = $output;

        parent::__construct($message, $code, $prev);
    }

    public function sendError(){
        if(empty($this->output['error']) && empty($this->output['errors'])){
            $this->output['error'] = $this->message;
        }

        http_response_code($this->code);

        print json_encode($this->output);
        die();
    }

    static function sendDefaultError($error = 'Unexpected Server Error'){
        http_response_code(500);

        print json_encode([
            'error' => $error
        ]);
        die();
    }
}

function defaultExceptionHandler($ex){
    if($ex instanceof ApiException){
        $ex->sendError();
    }

    ApiException::sendDefaultError($ex->getMessage());
}

set_exception_handler('defaultExceptionHandler');
