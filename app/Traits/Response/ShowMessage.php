<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 16/9/14
 * Time: 16:40
 */

namespace App\Traits\Response;


trait ShowMessage
{
    public function showMessage($message, $subMsg = '', $btnName = '', $btnLink = '')
    {
        $data = [
            'message' => $message,
            'subMsg'  => $subMsg,
            'btnName' => $btnName ?: trans('message.back_to_home'),
            'btnLink' => $btnLink ?: route('home'),
        ];

        return view('common.message', $data);
    }
}
