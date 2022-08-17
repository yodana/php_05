<?php

namespace ex01\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/e01/")
     */

    public function putButton(){
        return $this->render('ex01:Default:index.html.twig',[
            "error" => "",
            ]
        );
    }

     /**
     * @Route("/e01/create/")
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
        return $this->render('ex01:Default:index.html.twig',[
            "error" => $error,
            ]
        );
    }
}
