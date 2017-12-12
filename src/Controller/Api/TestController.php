<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Model\User;
use App\Model\WalletAddress;
use App\Repositories\AdvertRepository;
use App\Service\AdvertService;
use App\Validator\UserRegister;

use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Psr\Http\Message\ResponseInterface;
use DI\Annotation\Inject;
use Slim\Views\Twig;


class TestController extends Controller
{

    use UserRegister;
    /**
     * Annotation combined with phpdoc:
     *
     * @Inject
     * @var Translator
     */
    protected $translator;



    public function delete($request, $response)
    {


        $user=$this->user->findOrfail(3);
        dump($user);

        return $this->twig->render($response, 'home.twig');
    }

    public function test(Request $request,Response $response)
    {
         for($i=0;$i<1000;$i++){
             $data['coin_type']=2;
             $data['address']=uniqid().generate_code();

             WalletAddress::create($data);
         }
    }

    public function ok($id,Request $request){

        $data=[
            "username"=>''
        ];
//        $validator = Validation::createValidator();
//        $violations = $validator->validate('asdf', array(
//
//            new IsTrue(['message'=>'asdfasfdafd']),
//            new Email(['message'=>'234234'])
//        ));
//
//
//
//
//        $violations = $validator->validate($author);
        $violations=$this->inputCheck($request);


//        $translator = new Translator('zh_CN');
//        $translator->addLoader('array', new ArrayLoader());
//        $translator->addResource('array', array(
//            'hello33' => 'J\'aime Symfony!',
//        ), 'zh_CN');
//
//        $translator->addLoader('php', new PhpFileLoader());
//// Add language files here
//
//        $translator->addResource('php', C('root').'/translations/zh_cn/message.php', 'zh_CN'); // Norwegian

        //dump($this->translator->trans('hello'));
//        $validator = Validation::createValidator();
//        $violations = $validator->validate('asdf', array(
//
//            new IsTrue(['message'=>'asdfasfdafd']),
//            new Email(['message'=>'234234'])
//        ));
//
//        $author = new Post();
//
//
//        $errors = $validator->validate($author);
//
       //dump($errors[0]->getMessage());
//
//
        if (0 !== count($violations)) {


            // there are errors, now you can show them
            foreach ($violations as $violation) {
                dump($violation);
                //echo $violation->getMessage().'<br>';
            }
        }
    }

}
