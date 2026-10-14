<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use App\Helpers\AcceptHeaderParser;
use App\Helpers\Serializers\XmlSerializer;
use App\Helpers\Serializers\YamlSerializer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Participant in processing a server request and response.
 *
 * An HTTP middleware component participates in processing an HTTP message:
 * by acting on the request, generating the response, or forwarding the
 * request to a subsequent middleware and possibly acting on its response.
 */

class ContentNegotiationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {}
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $acceptHeader = $request->getHeader('Accept')[0] ?? '';

        $matchingFormat = AcceptHeaderParser::getBestMatch($acceptHeader, APP_MEDIA_TYPES);

        // i.e. if null
        if (!$matchingFormat) {
            // Method #1
            throw new HttpNotAcceptableException($request);

            // Method #2
            // $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
            // $response = $psr17Factory->createResponse();// You can pass a status code to the createResponse method.

            // $errorResponse = [
            //     'code' => 406,
            //     'message' => 'Not Acceptable',
            //     'description' =>  "The server cannot produce a response matching the criteria defined in the request's content negotiation headers.",
            // ];

            // // Taken from base controller

            // $payload = json_encode($errorResponse, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
            // //-- Write JSON data into the response's body.
            // $response->getBody()->write($payload);
            // return $response->withStatus(406)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
        }

        // Optional: Handle the incoming request
        //* ...Before middleware!
        $request = $request->withAttribute('format', $matchingFormat);

        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        // Optional: Handle the outgoing response
        //* ...After middleware!

        if($matchingFormat === APP_MEDIA_TYPE_JSON) {
            // no need to decode into php array, simply send it back
            return $response->withAddedHeader('Vary', 'Accept');
        }

        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        $content = null;

        if($matchingFormat === APP_MEDIA_TYPE_XML) {
            $content = XmlSerializer::serialize($data);
        }

        if($matchingFormat === APP_MEDIA_TYPE_YAML) {
            $content = YamlSerializer::serialize($data);
        }

        $response = $this->responseFactory->createResponse($response->getStatusCode());
        $response->getBody()->write($content);

        return $response
            ->withHeader('Content-Type', $matchingFormat)
            ->withAddedHeader('Vary', 'Accept');
    }
}
