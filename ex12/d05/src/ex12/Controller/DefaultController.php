<?php

namespace ex12\Controller;
use ex12\Entity\addresses;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use ex11\Entity\post;

class DefaultController extends Controller
{
    public $a = ["id", "username", "name", "email", "enable", "birthdate", "address", "lol", "8"];
    
    public function getTable($bool){
        $table = [];
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        $fields = array('d.id', 'd.username', 'd.name', 'd.email', 'd.birthdate', 'd.enable', 'd.marital_status', 'e.a_name', 'b.amount', 'b.id');
        try{
            $result = $qb
            ->select($fields)
            ->from('ex12:post', 'd')
            ->leftJoin('ex12:addresses', 'e', 'WITH', 'e.post = d.id')
            ->leftJoin('ex12:bank_account', 'b', 'WITH', 'b.post = d.id');
            if($bool == 1){
                $result = $result->orderBy('d.username');
            }
            if($bool == 0){
                $result = $result->orderBy('d.birthdate');
            }
            $result = $result->getQuery()->getResult();
        }
        catch(\Exception $e){
            echo 'Exception reçue : ',  $e->getMessage();
        }
        foreach($result as $t){
            array_push($table, [
                'username' => $t["username"],
                'name' => $t["name"],
                'email' => $t["email"],
                'enable' => $t["enable"],
                'birthdate' => $t["birthdate"]->format('Y-M-D'),
                'a_name' => $t["a_name"],
                "amount" => $t["amount"],
                "bank_id" => $t["id"]
            ]);
        }
        //var_dump($table[]["amount"]->getValues());
        return $table;
    }

    /**
     * @Route("/e12/")
     */
    public function indexAction(Request $request){
        $table = [];
        $table = $this->getTable(2);
        $form = $this->createFormBuilder()
            ->add('filtrage',ChoiceType::class,
                [ 
                    'choices' => 
                    [   
                        'Filtrer par date' => false,
                        'Filtrer par ordre alphabetique' => true,        
                    ],
                ])
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $table = $this->getTable($form["filtrage"]->getData());
        }
        return $this->render('ex12:Default:index.html.twig',[
                "table" => $table,
                "form" => $form->createView(),
                "error" => ""
            ]);
    }
}

