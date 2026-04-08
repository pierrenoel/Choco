<?php

namespace Choco\Core;

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
                              
                $urlParams = \array_slice($matches, 1); 

                $params = [...$urlParams,$_POST];
                
                $controller = new $route['controller']();
                $action = $route['action'];
                
                return $controller->$action(...$params);
            }
        }
    }

    private function addToRoutes(string $url, array $controller, string $method)
    {
        $originalUrl = \trim($url, "/");

        $regexUrl = \preg_replace('/\{[^}]+\}/', '([^/]+)', $originalUrl);

        $pattern = "@^" . $regexUrl . "$@";

        $this->routes[] = [
            "url"        => $originalUrl,
            "pattern"    => $pattern, 
            "method"     => $method,
            "controller" => $controller[0],
            "action"     => $controller[1]
        ];
    }

    public function post(string $url, array $controller)
    {
        $this->addToRoutes($url,$controller,"POST");
    }

    public function get(string $url, array $controller) 
    {
        $this->addToRoutes($url,$controller,"GET");
    }
}