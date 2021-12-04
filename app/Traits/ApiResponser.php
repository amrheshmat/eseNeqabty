<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09-Apr-19
 * Time: 8:39 AM
 */
namespace App\Traits;

trait ApiResponser
{

    /**
     * @param array $content
     * @param int $code
     * @param string $message
     * @return mixed
     */
    public function successResponse($content = [], $code =200,$message='')
    {
        //check if $content is not an array then wrap it into an array
        if(!is_array($content)){
            $content=[$content];
        }
        return $this->response($content,$code,$message,[]);
    }

    /**
     * @param int $code
     * @param array $errors
     * @param string $message
     * @return mixed
     */
    public function errorResponse($message,$code=400,$errors=[])
    {
        $validation=[];
        foreach ($errors as $key => $value) {
            $validation[] = [
                'field' => $key,
                'message' => $value[0]
            ];
        }
        return $this->response([],$code, $message, $validation);
    }

    /**
     * @param array $content
     * @param int $code
     * @param string $message
     * @param array $validation
     * @return mixed
     */

    public function response($content,$code,$message,$validation){
        $status=new \stdClass();
        $status->code=$code;
        $status->message=$message;
        $status->validation=$validation;
        return response()->json(['status' => $status, 'content' => $content], $code);
    }
}

