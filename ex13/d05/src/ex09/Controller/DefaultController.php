<?php

namespace ex09\Controller;

use ex09\Entity\addresses;
use ex09\Entity\post;
use ex09\Entity\bank_account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/e09/")
     */

    public function putButton(){
        return $this->render('ex09:Default:index.html.twig',[
            "error" => "",
            "buttons" => false,
            ]
        );
    }

     /**
     * @Route("/e09/create/")
     */
    public function indexAction()
    {
        $application = new ConsoleApplication($this->get('kernel'));
        $application->setAutoExit(false);
        $error =  "Success: the table is created succefully";
        $input = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();
        if (strpos($content, "Nothing to update - your database is already in sync with the current entity metadata.") != false)
            $error =  "Error: the table already exist";
        return $this->render('ex09:Default:index.html.twig',[
            "error" => $error,
            "buttons" => true,
            ]
        );
    }


     /**
     * @Route("/e09/create/column")
     */
    public function addColumn()
    {
        $application = new ConsoleApplication($this->get('kernel'));
        $application->setAutoExit(false);
        $error =  "Success: the column is adding succefully";
        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
        ]);
        $output = new BufferedOutput();
        try{
            $application->run($input, $output);
            $content = $output->fetch();
            if (strpos($content, "Already at the latest version") != false)
                $error =  "Error: the column already exist";
        }
        catch(\Exception $e){
            $error = "Error in adding column.";
        }
        return $this->render('ex09:Default:index.html.twig',[
            "error" => $error,
            "buttons" => true,
            ]
        );
    }

    /**
     * @Route("/e09/create/tables")
     */
    public function createBankAddresses()
    {
        $application = new ConsoleApplication($this->get('kernel'));
        $application->setAutoExit(false);
        $error =  "Success: the table bank_account and addresses is created succefully";
        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--force' => true,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();
        if (strpos($content, "Nothing to update - your database is already in sync with the current entity metadata.") != false)
            $error =  "Error: the tables already exist";
        return $this->render('ex09:Default:index.html.twig',[
            "error" => $error,
            "buttons" => true,
            ]
        );
    }
    /**
     * @Route("/e09/create/test")
     */
    public function test()
    {
        $category = new post();
        $category->setName('Computer Peripherals');
        $category->setUsername('Computer Peripherals');
        $category->setEmail('Computer Peripherals');
        $category->setEnable(1);
        $category->setBirthdate(new \DateTime(10000000));
        $category->setMaritalStatus(0);
        $product = new bank_account();
        $product->setAmount(19);

        // relates this product to the category
        $product->setPost($category);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();
        return $this->render('ex09:Default:index.html.twig',[
            "error" => "",
            "buttons" => true,
            ]
        );
    }
      /**
     * @Route("/e09/create/test2")
     */
    public function test2()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Post::class)->find(1);
        $product = new bank_account();
        $product->setAmount(19);

        // relates this product to the category
        $product->setPost($category);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();
        return $this->render('ex09:Default:index.html.twig',[
            "error" => "",
            "buttons" => true,
            ]
        );
    }
     /**
     * @Route("/e09/create/test3")
     */
    public function test3()
    {
        $category = new post();
        $category->setName('Computer Peripherals');
        $category->setUsername('Comper Peripherals');
        $category->setEmail('Computer ripherals');
        $category->setEnable(1);
        $category->setBirthdate(new \DateTime(10000000));
        $category->setMaritalStatus(0);
        $product = new addresses();
        $product->setName("loland");

        // relates this product to the category
        $product->setPost($category);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();
        return $this->render('ex09:Default:index.html.twig',[
            "error" => "",
            "buttons" => true,
            ]
        );
    }
}
