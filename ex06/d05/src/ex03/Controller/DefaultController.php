<?php

namespace ex03\Controller;
require_once(__DIR__ . '/../Repository/PostRepository.php');

use ex03\Entity\Post;
use ex03\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class DefaultController extends Controller
{
    /**
     * @Route("/e03/")
     */
    public function indexAction(Request $request)
    {
        $error = "";
        $application = new Application($this->get('kernel'));
        $post = $this->getDoctrine()
        ->getRepository(Post::class)
        ->createTable($application);
        $form = $this->createFormBuilder()
            ->add('username', TypeTextType::class)
            ->add('name', TypeTextType::class)
            ->add('email', TypeTextType::class)
            ->add('enable', ChoiceType::class,
                [ 
                    'choices' => 
                    [   
                        'Yes' => true,
                        'No' => false,        
                    ],
                ])
            ->add('birthdate', DateType::class)
            ->add('address', TypeTextType::class)
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $person = new Post();
            $person->setUsername($form["username"]->getData());
            $person->setName($form["name"]->getData());
            $person->setEmail($form["email"]->getData());
            $person->setEnable($form["enable"]->getData());
            $person->setBirthdate($form["birthdate"]->getData());
            $person->setAddress($form["address"]->getData());
            $em->persist($person);
            $validator = $this->get('validator');
            $errors = $validator->validate($person);
            if(count($errors) == 0)
                $em->flush();
            else
                $error = "Error: Username or Email exist already";
        }
        return $this->render('ex03:Default:index.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
    /**
     * @Route("/e03/show/")
     */
    public function showTable(){
        $table = [];
        $a = ["id", "username", "name", "email", "enable", "birthdate", "address"];
        $post = $this->getDoctrine()
        ->getRepository(Post::class);
        $r = (array)$post->findAll();
        foreach($r as $t){
            $i = 0;
            $tab = [];
            foreach((array)$t as $e){
                $tab[$a[$i]] = $e;
                $i++;
            }
            array_push($table, [
                'id' => $tab["id"],
                'username' => $tab["username"],
                'name' => $tab["name"],
                'email' => $tab["email"],
                'enable' => $tab["enable"],
                'birthdate' => $tab["birthdate"]->format('Y-m-d'),
                'address' => $tab["address"],
            ]);
        }
        return $this->render('ex03::table.html.twig', [
            'table' => $table,
        ]);
    }
}
