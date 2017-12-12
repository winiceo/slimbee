<?php

namespace App\Controller\Api;

use App\Exception\AccessDeniedException;
use Awurth\SlimValidation\Validator;
use Cartalyst\Sentinel\Sentinel;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Csrf\Guard;
use Slim\Exception\NotFoundException;
use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

/**
 * @property Guard csrf
 * @property Logger monolog
 * @property Messages flash
 * @property Router router
 * @property Sentinel auth
 * @property Twig twig
 * @property Validator validator
 */
abstract class Controller
{
    /**
     * Slim application container.
     *
     * @var ContainerInterface
     */
    protected $container;

    protected $user;

    protected $code = 200;

    protected $data = [];
    protected $message='';

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->user = $this->auth->getUser();
    }

    /**
     * Gets request parameters.
     *
     * @param Request $request
     * @param string[] $params
     * @param string $default
     *
     * @return string[]
     */
    protected function params(Request $request, array $params, $default = null)
    {
        $data = [];
        foreach ($params as $param) {
            $data[$param] = $request->getParam($param, $default);
        }

        return $data;
    }

    /**
     * Redirects to a route.
     *
     * @param Response $response
     * @param string $route
     * @param array $params
     *
     * @return Response
     */
    protected function redirect(Response $response, $route, array $params = [])
    {
        return $response->withRedirect($this->router->pathFor($route, $params));
    }

    /**
     * Redirects to a url.
     *
     * @param Response $response
     * @param string $url
     *
     * @return Response
     */
    protected function redirectTo(Response $response, $url)
    {
        return $response->withRedirect($url);
    }

    /**
     * Writes JSON in the response body.
     *
     * @param Response $response
     * @param mixed $data
     * @param int $status
     *
     * @return Response
     */
    protected function json(Response $response, $data, $status = 200)
    {
        $this->setCode($status);
        $this->setMessage("");
        $this->setData($data);
        return $response->withJson($this->formatResponse(), $status);
    }

    protected function success(Response $response, $data, $status = 200)
    {

        return $response->withJson($this->formatResponse(), $status);
    }


    /**
     * Return validation errors as a JSON array
     *
     * @param Response $response
     * @return Response
     */
    public function error(Response $response)
    {
        return $this->json($response, $this->validator->getErrors(), 400);
    }
    public function getErrorMessage(){
        $errors=(array)$this->validator->getErrors();
        $message = $this->getMessage(array_keys($errors)[0]);
        return $message;
    }

    public function getMessage($key){
        $data=[
            'username'=>'用户名错误',
            'password'=>'密码格式'
        ];
        return $data[$key];
    }

    protected function fail(Response $response, $message=null, $status = 400)
    {

        if(!$message){
            $message=$this->getErrorMessage();

        }
        $this->setCode($status);
        $this->setMessage($message);
        return $response->withJson($this->formatResponse(), $status);
    }



    /**
     * Writes text in the response body.
     *
     * @param Response $response
     * @param string $data
     * @param int $status
     *
     * @return int
     */
    protected function write(Response $response, $data, $status = 200)
    {
        return $response->withStatus($status)->getBody()->write($data);
    }

    /**
     * Adds a flash message.
     *
     * @param string $name
     * @param string $message
     */
    protected function flash($name, $message)
    {
        $this->flash->addMessage($name, $message);
    }

    /**
     * Creates a new NotFoundException.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return NotFoundException
     */
    protected function notFoundException(Request $request, Response $response)
    {
        return new NotFoundException($request, $response);
    }

    /**
     * Creates a new AccessDeniedException.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return AccessDeniedException
     */
    protected function accessDeniedException(Request $request, Response $response)
    {
        return new AccessDeniedException($request, $response);
    }

    /**
     * Gets a service from the container.
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        return $this->container->get($property);
    }


    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function setMessage($msg)
    {
        $this->message = $msg;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }




    public function formatResponse()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data
        ];
    }

}
