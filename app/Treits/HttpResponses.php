<?php 
namespace App\Treits;

trait HttpResponses {
    protected function Success($data,$message=null,$code=200)   {
        return response()->json([
            'status' => 'request_success',
            'message' => $message,
            'data' => $data  
        ],$code);
    }
    protected function Error($data,$message=null,$code)   {
        return response()->json([
            'status' => 'Error has occured',
            'message' => $message,
            'data' => $data  
        ],$code);
    }
}