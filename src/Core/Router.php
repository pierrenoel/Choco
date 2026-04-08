<?php

namespace Cariboo\Choco\Core;

class Router 
{
    protected mixed $request_uri;
    protected string $request_method;
    protected array $url;
    protected mixed $params;
    
    protected array $routes;

    public function __construct(){
        $this->initRequest();
    }

    private function initRequest()
    {
        $this->request_uri = $_SERVER["REQUEST_URI"];
        $this->request_method = $_SERVER["REQUEST_METHOD"];
        $this->url = \parse_url($this->request_uri);
        
        isset($this->url["query"]) ? \parse_str($this->url["query"],$this->params) : $this->params = [];
    }

    public function run() 
    {
      $requestPath = \trim($this->url["path"], "/");

        foreach ($this->routes as $route) {
            if ($route['method'] === $this->request_method && \preg_match($route['pattern'], $requestPath, $matches)) {
                              
                $params = \array_slice($matches, 1); 
                
                $controller = new $route['controller']();
                $action = $route['action'];
                
                return $controller->$action(...$params);
            }
        }
    }

    public function get(string $url, array $controller) 
    {
        $originalUrl = \trim($url, "/");

        $regexUrl = \preg_replace('/\{[^}]+\}/', '([^/]+)', $originalUrl);

        $pattern = "@^" . $regexUrl . "$@";

        $this->routes[] = [
            "url"        => $originalUrl,
            "pattern"    => $pattern, 
            "method"     => "GET",
            "controller" => $controller[0],
            "action"     => $controller[1]
        ];
    }
}