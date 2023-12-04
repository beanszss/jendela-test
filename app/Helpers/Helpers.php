<?php

if(!function_exists("responseCustom")) {
    function responseCustom($data = [], $message = null,$status = false): array
    {
        return [
            "status" => $status,
            "message" => $message,
            "data"   => $data
        ];
    }
}

if(! function_exists('alertNotify')){
    function alertNotify($isSuccess  = true, $message = '', $request = ''){
        if($isSuccess){
            request()->session()->flash('alert-class','success');
            request()->session()->flash('status', $message);
        }else{
            request()->session()->flash('alert-class','error');
            request()->session()->flash('status', $message);
        }
    }
}
