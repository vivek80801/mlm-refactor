#!/usr/bin/env php

<?php

require_once __DIR__ . "/src/app/Core/helper.php";

$isCommandFound = false;
$command = $argv[1];
$file = "";

if(isset($argv[2]))
{
    $file = $argv[2] . ".php";
}

$commandList = [
    [
        "name" => "list",
        "dir" => "",
        "function" => "listAllCommands",
        "functionArgs" => "",
    ],
    [
        "name" => "make:controller",
        "dir" => "src/app/Controllers",
        "function" => "makeController",
        "functionArgs" => "src/app/Controllers/" . $file,
    ],
    [
        "name" => "make:service",
        "dir" => "src/app/Services",
        "function" => "makeService",
        "functionArgs" => "src/app/Services/" . $file,
    ],
    [
        "name" => "make:view",
        "dir" => "src/views/",
        "function" => "makeView",
        "functionArgs" => "src/views/" . $file,
    ],
    [
        "name" => "make:model",
        "dir" => "src/app/Models/",
        "function" => "makeModel",
        "functionArgs" => "src/app/Models/" . $file,
    ],
];

function showCommands(array $commandList)
{
    foreach ($commandList as $command)
    {
        echo $command["name"] . PHP_EOL;
    }
}

function makeContent(
    string $path,
    string $content
)
{
    if(file_exists($path))
    {
        echo $path . " Already Exists\n";
    } else {
        file_put_contents($path, $content);
        echo $path . " is created\n";
    }
}

function makeService(string $path, string $class)
{
    $serviceData = "<?php

namespace App\Services;

class  {$class}
{
}";
    makeContent($path, $serviceData);
}

function makeModel(string $path, string $model)
{
   $classData = "<?php

namespace App\Models;

use App\Core\Models;

class  {$model} extends Models
{
    protected static string \$table = '" . lcfirst($model) . "s';
    // write code here
}";
    makeContent($path, $classData);
}

function makeView(string $path, string $view)
{
    $viewData = "<?php include_once viewPath() . 'layouts/header.php'  ?>
   <!-- Write Code Here -->
<?php include_once viewPath() . 'layouts/footer.php'  ?>";

    makeContent($path, $viewData);
}

function makeController(string $path, string $class) 
{
   $classData = "<?php

namespace App\Controllers;

use App\Core\Controller;

class  {$class} extends Controller
{
    public function index()
    {
        dd('index'); // Write Your Code Here
    }
}";
    makeContent($path, $classData);
}

foreach($commandList as $cmd)
{
    if($cmd["name"] === $command)
    {
        if(function_exists($cmd["function"]))
        {
            if(!is_dir($cmd["dir"]))
            {
                mkdir($cmd["dir"]);
            }
            call_user_func($cmd["function"], $cmd["functionArgs"], $argv[2]);
            $isCommandFound = true;
            break;
        } else if($cmd["name"] === "list") {
            showCommands($commandList);
            $isCommandFound = true;
        }else {
            echo "Error: function does not exists\n";
        }
    }
}

if(!$isCommandFound)
{
    echo "Error: No command found with " . $command . PHP_EOL;
}
