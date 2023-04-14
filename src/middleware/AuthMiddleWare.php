<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleWare
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $headerAuth = $request->getHeader('auth');
        //var_dump($headerAuth);

        if ( $headerAuth ) {
            // 일단 통과시키고 ..
            $response = $handler->handle($request);

            // 나중에 추가. 
            $response = $response->withAddedHeader( 'AuthRes', 'AT-' . $headerAuth );
        } else {
            // 일단 통과시키고 ..
            $response = $handler->handle($request);
            //$response = new Response();   // 모듈 안태우고 가버릴꺼면 reponse 를 냅다 생성 ... 

            // handle 내에서 html body가 출력되면 header 값 추가가 안된다 ... 
            // 즉 애플리캐에션 내부에서는 echo 말고, $reponse 객체를 사용해서 처리해야 함.. 

            // 나중에 추가. 
            $response = $response->withAddedHeader( 'AuthRes', 'NO AUTH' );
        }

        //$existingContent = (string) $response->getBody();
        //$response = new Response();
        //$response->getBody()->write('BEFORE' . $existingContent);
    
        return $response;
    }
}