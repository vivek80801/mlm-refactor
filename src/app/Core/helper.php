<?php

use App\Core\Exceptions\FileNotFoundException;

function dd(...$args)
{
    echo "<pre>";
    var_dump($args);
    echo "</pre>";
}

function basePath()
{
    return explode("src", __DIR__)[0];
}

function view(string $name, array $data = []): void
{
    $viewPath = basePath() . "src/views/" . $name . ".php" ;
    if(file_exists($viewPath))
    {
        ob_start();
        include_once $viewPath;
        $content = ob_get_clean();
        echo $content;
    }else {
        echo "Error: View Not Found";
    }
}

function viewPath()
{
    return  basePath() . "src/views/";
}

function assetPath()
{
    return  "assets";
}

function loadEnv()
{
    $file = basePath() .".env";
    if(file_exists($file))
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; 
            list($env, $newValue) = explode('=', $line, 2);
            $_ENV[trim($env)] = trim($newValue);
        }
    } else {
        throw new FileNotFoundException($file);
    }
}

function env(string $key, string $value = "")
{
    if(
        strlen($key) > 0 &&
        strlen($value) > 0
    )
    {
        $_ENV[trim($key)] = trim($value);
    }

    if(strlen($key) > 0)
    {
        if($_ENV[trim($key)] === "true")
        {
            return true;
        }
        if($_ENV[trim($key)] === "false")
        {
            return false;
        }
        return $_ENV[trim($key)] ;
    }
}

function redirect(string $url)
{
    header("location: " . $url);
}

function generateRandomHelper(
    int $len,
    string $chars
) {
    $result = "";
    for($i = 0; $i < $len; $i++)
    {
        $result .= $chars[rand(0, strlen($chars))];
    }
    return $result;
}

function generateRandomStr(int $len)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return generateRandomHelper($len, $chars);
}

function generateRandomNum(int $len)
{
    $chars = "0123456789";
    return (int) generateRandomHelper($len, $chars);
}

function generateRandomNumWithStr(int $len)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890";
    return generateRandomHelper($len, $chars);
}

function generateRandomWhole(int $len)
{
    $chars = "
    abcdefghjklmnopqrstuvwxyz
    ABCDEFGHIJKLMNOPRSTUVWXYZ
    01234567890
    `~!@#$%^&*()-_=+[]{}\\|'\";:.<>,/
    ";
    return (int) generateRandomHelper($len, $chars);
}

