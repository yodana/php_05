<?php

namespace ex07\Controller;

use ex07\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class DefaultController extends Controller
{
    public $a = ["id", "username", "name", "email", "enable", "birthdate", "address"];
    
    public function getTable(){
        $table = [];
        $post = $this->getDoctrine()
        ->getRepository(Post::class);
        $r = (array)$post->findAll();
        foreach($r as $t){
            $i = 0;
            $tab = [];
            foreach((array)$t as $e){
                $tab[$this->a[$i]] = $e;
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
        return $table;
    }

    /**
     * @Route("/e07/")
     */
    public function indexAction(){
        $table = $this->getTable();
        return $this->render('ex07::table.html.twig',[
                "table" => $table,
                "message" => ""
        ]);
    }
    /**
     * @Route("/e07/update/{id}")
     */
    public function update($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $person = $entityManager->getRepository(Post::class)->find($id);
        //$product->setName('New product name!');
        //$entityManager->flush();
        $i = 0;
        $table = [];
        foreach((array)$person as $e){
            $table[$this->a[$i]] = $e;
            $i++;
        }
        $form = $this->createFormBuilder()
        ->add('username', TypeTextType::class, 
            [   
                'attr' => array(
                    'value' => $table["username"]
                )
            ])
        ->add('name', TypeTextType::class, 
            [   
                'attr' => array(
                    'value' => $table["name"]
                )
            ])
        ->add('email', TypeTextType::class, 
            [   
                'attr' => array(
                    'value' => $table["email"]
                )
            ])
        ->add('enable', ChoiceType::class,
            [ 
                'choices' => 
                [   
                    'Yes' => true,
                    'No' => false,        
                ],
                'attr' => array(
                    'value' => $table["enable"]
                )
            ])
        ->add('birthdate', DateType::class, 
            [   
                'data' => $table["birthdate"]
            ])
        ->add('address', TypeTextType::class, 
            [   
                'attr' => array(
                    'value' => $table["address"]
                )
            ])
        ->add('update', SubmitType::class)
        ->getForm();
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $person->setUsername($form["username"]->getData());
            $person->setName($form["name"]->getData());
            $person->setEmail($form["email"]->getData());
            $person->setEnable($form["enable"]->getData());
            $person->setBirthdate($form["birthdate"]->getData());
            $person->setAddress($form["address"]->getData());
            try{
                $entityManager->flush();
            }
            catch(\Exception $e){
                return $this->render('ex07::table.html.twig',[
                    "table" => $this->getTable(),
                    "message" => "Error Update."
                ]);
            }
            return $this->render('ex07::table.html.twig',[
                "table" => $this->getTable(),
                "message" => "Update Sucess"
            ]);
        }
        return $this->render('ex07::form.html.twig',[
                "form" => $form->createView(),
        ]);
    }
}
