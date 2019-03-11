<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UserScopes;
use DomainException;
use Exception;
use Firebase\JWT\ExpiredException;
use Http\Controllers\Exception\DuplicateEmailException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;
use JMS\Serializer\SerializerBuilder;
use Rest\Shared\Exception\AuthenticationFailureException;
use Rest\Shared\Exception\ConflictException;
use Rest\Shared\Exception\EntityNotFoundException;
use Rest\Shared\Exception\MissingEntityException;
use Rest\Shared\Exception\PermissionDeniedException;
use Laravel\Lumen\Routing\Controller as BaseController;
use ReflectionClass;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class Controller extends BaseController
{

    /**
     * @var UserScopes
     */
    protected $currentUserScopes;

    /**
     * @var string
     */
    protected $currentUserId;

    private static $exceptionMap = [
        EntityNotFoundException::class => 404,
        MissingEntityException::class => 410,
        ConflictException::class => 409,
        PermissionDeniedException::class => 403,
        AuthenticationFailureException::class => 401,
        InvalidArgumentException::class => 400
    ];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * Controller constructor.
     * @param ContainerInterface $container
     * @param Request $request
     * @param Response $response
     */
    public function __construct(ContainerInterface $container, Request $request, Response $response)
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
        $this->currentUserScopes = new UserScopes();

        if ($request->has('token')) {
            $this->currentUserScopes->addFromArray($request->get('token')['scope']);
            $this->currentUserId = $request->get('token')['sub'];
        }
    }

    /**
     * @return UserScopes
     */
    public function currentUserScopes(): UserScopes
    {
        return $this->currentUserScopes;
    }

    /**
     * @return string
     */
    public function currentUserId(): string
    {
        return $this->currentUserId;
    }

    /**
     * @param string $requestedContext
     * @param string $requestedModel
     * @param string $intendedAction
     * @return bool
     */
    protected function checkPermission(
        string $requestedContext,
        string $requestedModel,
        string $intendedAction = 'read'
    ) {
        foreach ($this->currentUserScopes()->scopes() as $scope) {
            if ($scope->context() === $requestedContext && $scope->model() === $requestedModel) {
                if ($scope->isAdmin()) {
                    return true;
                }
                if ($scope->isAllowedTo('read')) {
                    return true;
                }
            }
        }

        throw new InvalidArgumentException(
            "You have no permission to perform this action {$requestedContext}.{$requestedModel}.
            {$intendedAction}",
            403
        );
    }


    /**
     * @return ContainerInterface
     */
    protected function container(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param Exception $exception
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \ReflectionException
     */
    protected function exception(Exception $exception)
    {
        $code = $this->isExceptionWithinHttpStatusCodeRange($exception) ?
            $exception->getCode() :
            (new ReflectionClass($exception))->getShortName();
        $httpCode = $this->isExceptionWithinHttpStatusCodeRange($exception) ? $exception->getCode() : 500;

        foreach (self::$exceptionMap as $exceptionClassName => $storedHttpCode) {
            if ($exception instanceof $exceptionClassName) {
                $httpCode = $storedHttpCode;
                break;
            }
        }

        if (env('APP_DEBUG')) {
            return $this->response(
                [
                    "_error" => [
                        "code" => $code,
                        "message" => $exception->getMessage(),
                        "file" => $exception->getFile(),
                        "line" => $exception->getLine(),
                    ]
                ],
                $httpCode
            );
        }

        return $this->response(
            [
                "_error" => [
                    "code" => $code,
                    "message" => $exception->getMessage()
                ]
            ],
            $httpCode
        );
    }

    /**
     * @param Exception $exception
     * @return bool
     */
    private function isExceptionWithinHttpStatusCodeRange(\Exception $exception)
    {
        return $exception->getCode() >= 200 && $exception->getCode() < 600;
    }

    /**
     * @param $response
     * @param int $responseCode
     * @param array $headers
     * @return Response|\Laravel\Lumen\Http\ResponseFactory
     */
    protected function response($response, $responseCode = 200, $headers = [])
    {
        $responseObject = \response($response, $responseCode, $headers);
        $requestedContentType = $this->request->header('Accept') === '*/*' ?
            'application/json' :
            $this->request->header('Accept');
        $stringContent = $responseObject->getContent();
        $arrayContent = json_decode($responseObject->content(), true);

        if ($arrayContent) {
            if (strpos($requestedContentType, 'yml') !== false) {
                $stringContent = self::makeYamlContent($responseObject, 'yml');
            }
            if (strpos($requestedContentType, 'yaml') !== false) {
                $stringContent = self::makeYamlContent($responseObject, 'yml');
            }
            if (strpos($requestedContentType, 'xml') !== false) {
                $stringContent = ArrayToXml::convert($arrayContent);
            }
        }
        return $responseObject->setContent($stringContent)->header('Content-type', $requestedContentType);
    }

    /**
     * @param Response $response
     * @param $format
     * @return mixed|string
     */
    private function makeYamlContent(Response $response, $format)
    {
        $serializer = SerializerBuilder::create()->build();
        $newContent = $serializer->serialize(json_decode($response->content(), true), $format);
        return $newContent;
    }
}
