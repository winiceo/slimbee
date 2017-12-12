<?php

namespace App\Controller\Api;

use App\Model\User;
use App\Service\UserWalletService;
use App\Validator\UserRegister;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController extends Controller
{

    use UserRegister;
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
                        "token"=>$this->jwtGenerator($this->auth->getUser(),$this->container->get('secret-key'))
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


            $violations=$this->inputCheck($request);

            if (0 !== count($violations)) {
                $this->error($violations[0]->getMessage());
            }


            if ($this->auth->findByCredentials(['login' => $username])) {
                $this->error('此用户名已存在');
            }

            if ($this->auth->findByCredentials(['login' => $email])) {
                $this->error('此邮箱已存在');
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
                    "token"=>$this->jwtGenerator($user,$this->container->get('secret-key'))
                ];

                return $this->json($response,$data);


            }
        }

        return $this->fail($response);
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
