<?php

namespace App\Application;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

use App\Modules\TestModule;         // container 에 등록되어서 초기화가 돌아간다 하더라도, use 선언이 있어야 오류가 안남.... 값은 유지가 된다 ... 
use Psr\Container\ContainerInterface;   // 걍 냅다 컨테이너로 접근해서 땡기는 경우 ... 

class BaseContainer {

    private $initvar = 1;   // base .. 

    public function __construct() {
        //echo ' baseContainer construct ';
    }

    public function setInit() {
        //echo ' setInit Called ';
        $this->initvar = 2; 
    }

    public function __invoke(Response $response) {
        $response->getBody()->write('function is maybe not founded .. ' . $this->initvar );

        return $response;
    }

    public function hello2( Request $request, Response $response ) : Response      // URL 파람으로 읽는법 ...
    {
        //https://www.slimframework.com/docs/v4/objects/request.html  .. get URL parameter from route. 
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $id = $route->getArgument('id');

        $response->getBody()->write('Hi ' . $id . ' - ' . $this->initvar );

        return $response;
    }

    public function hello( Request $request, Response $response, TestModule $testModule, ContainerInterface $containerInterface ) : Response 
    {
        //var_dump( $testModule->getWilly() );                                        // 직접 땡기는 경우 ... 
        //var_dump( $containerInterface->get(TestModule::class)->getWilly() );        // containerInterface 를 통해 땡기거나 ... 
                                                                                    // 일단 container 에 저장된 값이 적용된다 ... 

        $response->getBody()->write('hi hello [' . $testModule->getWilly() . '] - ' . $this->initvar );       // 직접 땡기거나, 

        return $response;
    }
}