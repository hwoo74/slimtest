<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use App\Application\BaseContainer;

// $app 에 Dependency Injection 이 걸려있는 class 는 use 선언없이 바로 사용가능... 
// 초기화가 자동으로 돌아가서 작동됨 ...    index.php case B. 

// 그외의 경우에는 ... 상단에 use 로 선언해 주고 땡겨써야 한다 ... 

// Dependency Inejction 이 걸려있는 애를 'string으로 호출하지 않고 직접 호출할 경우, 상단에 use 로 걸어주면 이미 걸어놓은 초기화가 날라가 버림 ... 

// 즉, 걍 땡길지, dependency injection 을 걸어서 쓸지 생각하고 가져다 쓸것... 

use App\Middleware\AuthMiddleWare;



return (function ( $app ) {
    $app->get('/', function (Request $request, Response $response) {
	    $response->getBody()->write("Hello world!");
	    return $response;
    });

    //$app->get('/test', function (Request $request, Response $response, $args) {		// slim 만 쓰면 돌아가지만, slim-bridge 쓰면 안돌아감 ... -_- ;;
    $app->get('/test', function (Request $request, Response $response) {				// 안쓰는 arg 를 날려야 slim-bridge가 돌아감 ... 
                                                                                        // https://github.com/PHP-DI/Slim-Bridge#controller-parameters
            $response->getBody()->write("Hello Sidney in New SSyang York");
            return $response;
    });

    /*
    $app->get('/hello/{id}', function ( $id, Response $response ) {
        $response->getBody()->write( $id . ' is given');
        return $response;
    });
    */
    //$app->get('/hello/{id}', BaseContainer::class . ':hello2' );
    $app->get('/hello/{id}', [ BaseContainer::class, 'hello2' ] );


    $app->get('/hello', BaseContainer::class . ':hello' )->add( AuthMiddleWare::class );
    //$app->get('/hello', [ BaseContainer::class, 'hello' ] );
    //$app->get('/hello', 'BaseContainer:hello' );		// 문자열로 호출할꺼면 Container 에 미리 등록을 해 둬야 한다... 구축 B .. 

    $app->any('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        $response = $response->withStatus(404, '낫 found');                     // 먹힘... 대신 앞에서 response write가 없어야 먹힘 ... 

        return $response;
    });
});