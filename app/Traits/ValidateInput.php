<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 16/9/20
 * Time: 13:15
 */

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait ValidateInput
{
    /**
     * @param array $data
     * @param array $rule
     * @param array $message
     * @param array $attr
     * @param bool  $throws
     * @return array|\Illuminate\Support\MessageBag
     * @throws ValidationException
     */
    public function validate($data, $rule, $message = [], $attr = [], $throws = true)
    {
        $validator = \Validator::make($data, $rule, $message, $attr);
        if (!$validator->fails()) {
            return [];
        }

        if ($throws) {
            throw new ValidationException($validator);
        }

        return $validator->errors();
    }
}
