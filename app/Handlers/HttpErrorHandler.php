<?php

namespace App\Handlers;

use App\Helpers\AcceptHeaderParser;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpSpecializedException;
use App\Helpers\Serializers\XmlSerializer;
use App\Helpers\Serializers\YamlSerializer;

class HttpErrorHandler {
    public function __construct(private ResponseFactoryInterface $responseFactory) {}

    /**
    * Error handler that produces error responses in the format negotiated with the client.
    */
    public function __invoke(
        Request $request,
        HttpSpecializedException $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) {
        $status = $exception->getCode();
        $message = $exception->getMessage();

        $errorArr = [
            'status_code' => $status,
            'error_type' => $exception::class,
            'message' => $message,
            'description' => $exception->getDescription(),
        ];

        $response = $this->responseFactory->createResponse($status, $message);

        // early return to JSON if 406
        if ($status === 406) {
            $content = json_encode($errorArr);
            $response->getBody()->write($content);
            return $response
                ->withAddedHeader("Content-Type", APP_MEDIA_TYPE_JSON);
        }

        $acceptHeader = $request->getHeader('Accept')[0] ?? '';
        $matchingFormat = AcceptHeaderParser::getBestMatch($acceptHeader, APP_MEDIA_TYPES);

        $content = match ($matchingFormat) {
            APP_MEDIA_TYPE_JSON => json_encode($errorArr),
            APP_MEDIA_TYPE_XML => XmlSerializer::serialize($errorArr),
            APP_MEDIA_TYPE_YAML => YamlSerializer::serialize($errorArr)
        };

        $response->getBody()->write($content);
        return $response
            ->withHeader('Content-Type', $matchingFormat)
            ->withAddedHeader('Vary', 'Accept');
    }
}
