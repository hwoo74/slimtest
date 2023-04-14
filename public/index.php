<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;
use DI\Container;
use DI\Bridge\Slim\Bridge;

use Psr\Container\ContainerInterface;
use App\Application\BaseContainer;
use App\Modules\TestModule;

require __DIR__ . '/../vendor/autoload.php';

function leahDebug( $className ) {
	$tmpr = new ReflectionClass($className);

	foreach( $tmpr->getConstants() as $key => $item ) {
		echo '<i><font color="blue">' . $key . ' - ' . $item . '</font></i></br>';
	}
	echo '<br />';
	foreach( $tmpr->getProperties() as $key => $item ) {
		echo '<i><font color="red">' . $key . ' - ' . $item . '</font></i></br>';
	}
	echo '<br />';
	foreach( $tmpr->getMethods() as $key => $item ) {
		//echo $key . " : \n";
		echo '<b>' . $item->name . '</b> : ' . $item->getReturnType() . '<br />';
		foreach ( $item->getParameters() as $item2 ) {
			echo $item2->getType();
			echo ' - ';
			echo $item2->getName();
			echo "<br />";
		}
		//$rtn_type = new ReflectionFunction( $item->name );		// NO !! function ONLY
		//echo 'rtn : ' . $rtn_type->getReturnType() . '<br />';
		echo '<pre>';
		var_dump( $item->getParameters() );
		echo '</pre><hr />';
	}
}


// https://studyposting.tistory.com/54		
//		.. autoload 로 선언해주고 ...
//		.. 변경 되었으면, composer dump-autoload 해줘야 함..
//$container = new Container();
$container = new DI\Container([
    // place your definitions here

	Request::class => DI\create(Request::class),
	Response::class => DI\create(Response::class),
	TestModule::class => DI\create(TestModule::class)->constructor('zio'),

	//BaseContainer::class => DI\create(BaseContainer::class)
	//'BaseContainer' => DI\create(BaseContainer::class)    // 이것도 router 에서 호출 가능 ...

	'BaseContainer' => function() {							// 이렇게 해두면 Dependency Injection 주입시 .. 얘가 먼저 돌아감 ... 
		$baseContainer = new BaseContainer();
		$baseContainer->setInit();
		return $baseContainer;
	}
]);

//leahDebug( $container->get('BaseContainer') ); //exit;


//$app = AppFactory::create();			// A. 일반 구축
$app = Bridge::create($container);	// B. DI 사용한 구축... 
//$app = Bridge::create();				// C. DI 무시한 구축... 

//leahDebug($app); exit;

// set subdirectory. added by hwoo.
$app->setBasePath('/slimtest');

// Add Error Handling Middleware
$app->addErrorMiddleware(true, false, false);

// Add Routing Middleware
//$app->addRoutingMiddleware();	// 의미없당. 

//$routeCollector = $app->getRouteCollector();


// Register routes
$routes = require __DIR__ . '/../src/router.php';
$routes($app);


/*
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

$app->get('/hello/{id}', function ( $id, Response $response ) {
	$response->getBody()->write( $id . ' is given');
	return $response;
});
//$app->get('/hello/{id}', BaseContainer::class . ':hello2' );


$app->get('/hello', BaseContainer::class . ':hello' );	
//$app->get('/hello', [ BaseContainer::class, 'hello' ] );
//$app->get('/hello', 'BaseContainer:hello' );		// 문자열로 호출할꺼면 Container 에 미리 등록을 해 둬야 한다... 구축 B .. 

$app->any('/{routes:.*}', function (Request $request, Response $response) {
	// CORS Pre-Flight OPTIONS Request Handler
	$response = $response->withStatus(404, '낫 found');                     // 먹힘... 대신 앞에서 response write가 없어야 먹힘 ... 

	return $response;
});
*/

$app->run();
