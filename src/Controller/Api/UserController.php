<?php

namespace App\Controller\Api;

use App\Model\User;
use App\Service\UserWalletService;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController extends Controller
{


    public function jwtGenerator(User $user, $secretKey)
    {
        $token = [
            "iss" => "http://pf.local",
            "iat" => time(),
            "nbf" => time(),
            'exp' => strtotime("+12 month"),
            "data" => [
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'roles' => $user->permissons,
            ],
        ];

        $jwt = JWT::encode($token, $secretKey);

        return $jwt;
    }


    /**
     * @api {post} /user/login 用户登录
     * @apiName GetUser
     * @apiGroup User
     *
     * @apiParam {String} username 用户登录名.
     * @apiParam {String} password 用户密码.
     *
     * @apiSuccess {String} err_code  状态码0为成功.
     * @apiSuccess {String} err_msg 信息提示.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *    {
     * "err_code": 0,
     * "err_msg": "success",
     * "data": {
     * "token": {
     * "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE0NDU5MTgyOTksImp0aSI6ImJFR2Z1ak5Bckp4QXl2UHdPSVFaekV2eEtYT2hrRlwvT1VcL1hzTUlsRXhUdz0iLCJpc3MiOiJwbWtvb19zZWxsZXIiLCJleHAiOjE0NDU5MjU0OTksImRhdGEiOnsiaWQiOiIxIiwibmFtZSI6ImFkbWluIn19.NtZxwC9WoDdnGVjboC1O3RJCcAMsj0D5G3A1vxL0RMMEiSwKC3jPLRMaByCXd9xm6IJw8BH0GLkVnWGvW5_aWA"
     * },
     * "user": {
     * "id": "1",
     * "username": "admin",
     * "email": "admin@71an.com"
     * }
     * }
     * }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     * {
     * "err_code": 1,
     * "err_msg": "登录失败"
     * }
     * @apiSampleRequest /v1/user/login
     */

    public function login(Request $request, Response $response)
    {

        if ($request->isPost()) {
            $credentials = [
                'username' => $request->getParam('username'),
                'password' => $request->getParam('password')
            ];
            $remember = $request->getParam('remember') ? true : false;

            try {
                if ($this->auth->authenticate($credentials, $remember)) {
                    $this->flash('success', 'You are now logged in.');
                    $data=[
                        "user"=>$this->auth->getUser(),
                        "token"=>$this->jwtGenerator($this->auth->getUser(),$this->container['secret-key'])
                    ];

                    return $this->json($response,$data);


                    //return $this->redirect($response, 'home');
                } else {
                    $this->validator->addError('auth', 'Bad username or password');
                }
            } catch (ThrottlingException $e) {
                $this->validator->addError('auth', 'Too many attempts!');
            }
        }
        return $this->error($response);


    }

    public function register(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $username = $request->getParam('username');
            $email = $request->getParam('email');
            $password = $request->getParam('password');

            $this->validator->request($request, [
                'username' => V::length(3, 25)->alnum('_')->noWhitespace(),
                'email' => V::noWhitespace()->email(),
                'password' => [
                    'rules' => V::noWhitespace()->length(6, 25),
                    'messages' => [
                        'length' => 'The password length must be between {{minValue}} and {{maxValue}} characters'
                    ]
                ]
            ]);

            if ($this->auth->findByCredentials(['login' => $username])) {
                $this->validator->addError('username', 'This username is already used.');
            }

            if ($this->auth->findByCredentials(['login' => $email])) {
                $this->validator->addError('email', 'This email is already used.');
            }

            if ($this->validator->isValid()) {
                $role = $this->auth->findRoleByName('User');

                $user = $this->auth->registerAndActivate([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'permissions' => [
                        'user.delete' => 0
                    ]
                ]);

                $role->users()->attach($user);

                $this->flash('success', 'Your account has been created.');

                $data=[
                    "user"=>$user,
                    "token"=>$this->jwtGenerator($user,$this->container['secret-key'])
                ];

                return $this->json($response,$data);


            }
        }

        return $this->error($response);
    }

    public function logout(Request $request, Response $response)
    {
        $this->auth->logout();

        return $this->redirect($response, 'home');
    }


    public function profile(Request $request, Response $response){
        $user=$this->auth->getUser();
        return $this->json($user);
    }





}
