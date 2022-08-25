<?php

namespace ex11\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ex11\Entity\addresses;
use ex11\Entity\post;
use ex11\Entity\bank_account;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/e11/")
     */
    public function indexAction(Request $request)
    {
        $error = "";
        $sql = "SELECT bank_account_e11.id, person_e11.username, person_e11.name, person_e11.email, person_e11.enable, person_e11.birthdate, person_e11.marital_statut, address.a_name, bank_account_e11.amount FROM person_e11 LEFT JOIN bank_account_e11 ON person_e11.id=bank_account_e11.bank_id LEFT JOIN address ON person_e11.id=address.addresses_id;";
        $em = $this->get('doctrine.orm.default_entity_manager');
        try {
            $statement = $em->getConnection()->prepare($sql);
            $statement->execute();
        }
        catch(Exception $e){
            $error = "Error";
        }
        $table = [];
        foreach($statement->fetchAll() as $t){
            array_push($table, [
                'username' => $t["username"],
                'name' => $t["name"],
                'email' => $t["email"],
                'enable' => $t["enable"],
                'birthdate' => $t["birthdate"],
                'marital_statut' => $t["marital_statut"],
                'amount' => $t["amount"],
                'bank_id' => $t["id"],
                'a_name' => $t["a_name"],
            ]);
        }
        $form = $this->createFormBuilder()
            ->add('filtrage',ChoiceType::class,
                [ 
                    'choices' => 
                    [   
                        'Filtrer par date' => true,
                        'Filtrer par ordre alphabetique' => false,        
                    ],
                ])
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            if($form["filtrage"]->getData() == true){
                $sql = "SELECT bank_account_e11.id, person_e11.username, person_e11.name, person_e11.email, person_e11.enable, person_e11.birthdate, person_e11.marital_statut, address.a_name, bank_account_e11.amount FROM person_e11 LEFT JOIN bank_account_e11 ON person_e11.id=bank_account_e11.bank_id LEFT JOIN address ON person_e11.id=address.addresses_id ORDER BY birthdate;";
                $em = $this->get('doctrine.orm.default_entity_manager');
                try {
                    $statement = $em->getConnection()->prepare($sql);
                    $statement->execute();
                }
                catch(\Exception $e){
                    $error = "Error";
                }
                $table = [];
                foreach($statement->fetchAll() as $t){
                    array_push($table, [
                        'username' => $t["username"],
                        'name' => $t["name"],
                        'email' => $t["email"],
                        'enable' => $t["enable"],
                        'birthdate' => $t["birthdate"],
                        'marital_statut' => $t["marital_statut"],
                        'amount' => $t["amount"],
                        'bank_id' => $t["id"],
                        'a_name' => $t["a_name"],
                    ]);
                }
                return $this->render('ex11:Default:index.html.twig', [
                    "error" => $error,
                    "table" => $table,
                    "form" => $form->createView()
                ]);
            }
            else{
                $sql = "SELECT bank_account_e11.id, person_e11.username, person_e11.name, person_e11.email, person_e11.enable, person_e11.birthdate, person_e11.marital_statut, address.a_name, bank_account_e11.amount FROM person_e11 LEFT JOIN bank_account_e11 ON person_e11.id=bank_account_e11.bank_id LEFT JOIN address ON person_e11.id=address.addresses_id ORDER BY username;";
                $em = $this->get('doctrine.orm.default_entity_manager');
                try {
                    $statement = $em->getConnection()->prepare($sql);
                    $statement->execute();
                }
                catch(\Exception $e){
                    $error = "Error";
                }
                $table = [];
                foreach($statement->fetchAll() as $t){
                    array_push($table, [
                        'username' => $t["username"],
                        'name' => $t["name"],
                        'email' => $t["email"],
                        'enable' => $t["enable"],
                        'birthdate' => $t["birthdate"],
                        'marital_statut' => $t["marital_statut"],
                        'amount' => $t["amount"],
                        'bank_id' => $t["id"],
                        'a_name' => $t["a_name"],
                    ]);
                }
                return $this->render('ex11:Default:index.html.twig', [
                    "error" => $error,
                    "table" => $table,
                    "form" => $form->createView()
                ]);
            }
        }
        return $this->render('ex11:Default:index.html.twig', [
            "error" => $error,
            "table" => $table,
            "form" => $form->createView()
        ]);
    }
}