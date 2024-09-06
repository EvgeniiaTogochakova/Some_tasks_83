<?php

namespace Geekbrains\Application1;

final class Application
{
    private static array $config;
    private const APP_NAMESPACE = 'Geekbrains\Application1\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public static function config(): array
    {
        return Application::$config;
    }

    public function run(): string
    {
        Application::$config = parse_ini_file('config.ini', true);
        $routeArray = explode('/', $_SERVER['REQUEST_URI']);

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        } else {
            $controllerName = "page";
        }

        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";

        if (class_exists($this->controllerName)) {

            if (isset($routeArray[2]) && $routeArray[2] != '') {
                $methodName = $routeArray[2];
            } else {
                $methodName = "index";
            }

            $this->methodName = "action" . ucfirst($methodName);

            if (method_exists($this->controllerName, $this->methodName)) {
                $controllerInstance = new $this->controllerName();
                return call_user_func_array(
                    [$controllerInstance, $this->methodName],
                    []
                );
            } else {
                header("HTTP/1.1 404 Not Found");
                header("Location: /404.html");
                die();
            }
        } else {
            header("HTTP/1.1 404 Not Found");
            header("Location: /404.html");
            die();
        }
    }
}