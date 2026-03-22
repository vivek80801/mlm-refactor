<?php

namespace App\Core;

use App\Core\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    protected array $queryParam;
    protected array $bodyParam;
    protected array $serverParam;
    private Session $session;

    public function __construct()
    {
        $this->queryParam = $_GET;
        $this->bodyParam = $_POST;
        $this->serverParam = $_SERVER;
        $this->session = new Session();

        $json = json_decode(
            file_get_contents("php://input"),
            true
        );

        if(is_array($json))
        {
            $this->bodyParam = array_merge(
                $this->bodyParam,
                $json
            );
        }
    }

    public function getUri(): string
    {
        $uri = $this->serverParams['REQUEST_URI'] ?? '/';
        return parse_url($uri, PHP_URL_PATH);
    }

    public function getMethod(): string
    {
        return strtoupper(
            $this->serverParam['REQUEST_METHOD']
        );
    }

    public function input(
        string $key,
        $default = null
    ): string | null
    {
        if(
            isset($this->bodyParam[$key])
        )
        {
            return htmlspecialchars(
                $this->bodyParam[$key]
            );
        }

        if(
            isset($this->queryParam[$key])
        )
        {
            return htmlspecialchars(
                $this->queryParam[$key]
            );
        }

        return $default;
    }

    public function all(): array
    {
        return array_merge(
            $this->bodyParam,
            $this->queryParam
        );
    }

    public function session(): Session
    {
        return $this->session;
    }

}
