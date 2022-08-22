<?php

namespace ex09\Controller;

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
}
