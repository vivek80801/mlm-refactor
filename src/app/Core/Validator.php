<?php

namespace App\Core;

use App\Core\Interfaces\ValidatorInterface;

class Validator implements ValidatorInterface
{
    public static function validate
    (
        array $args,
        Object $request
    ): array
    {
        $errors = [];
        foreach($args as $key => $arg)
        {
            $newArgs = explode("|", $arg);
            foreach($newArgs as $newArg)
            {
                $newErrors = self::applyRule($key, $newArg, $request);
                if(count($newErrors) > 0)
                {
                    if(!isset($errors[$key]))
                    {
                        $errors[$key] = $newErrors;
                    }else {
                        array_push($errors[$key], $newErrors[0]);
                    }
                }
            }
        }
        return $errors;
    }

    private static function applyRule
    (
        string $key,
        string $newArg,
        Object $request,
    ): array
    {
        $errors = [];
        switch($newArg)
        {
            case "required":
                if(
                    strlen($request->input($key)) <= 0 ||
                    $request->input($key) === null
                )
                {
                    array_push($errors, $key . " is required");
                }
                break;

            case str_contains($newArg, "digit"):
                list($first, $second) = explode(":", $newArg);
                if(
                    gettype(
                        (int) $request->input($key)) !== "integer"
                    )
                {
                    array_push($errors, $key . " must be a " . $first);
                }
                if(strlen($request->input($key)) !== (int) $second)
                {

                    array_push($errors, $key . " must be equal to " . $second . " " . $first);
                }
                break;

            case str_contains($newArg, "min"):
                list($first, $second) = explode(":", $newArg);
                if(strlen($request->input($key)) < (int) $second)
                {
                    array_push($errors, $key . " must be greater then " . $second . " characters");
                }
                break;

            case str_contains($newArg, "max"):
                list($first, $second) = explode(":", $newArg);
                if(strlen($request->input($key)) > (int) $second)
                {
                    array_push($errors, $key . " must be less then " . $second . " characters");
                }
                break;

            default:
                break;
        }
        return $errors;
    }
}
