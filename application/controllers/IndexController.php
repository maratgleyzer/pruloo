<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {

		$templates = scandir(realpath(APPLICATION_PATH . '/../public/_'));
		array_splice($templates,0,2);
		
   		$template = $templates[array_rand($templates)];
  		header("Location: /_/$template/");
  		exit;

    }

    public function indexAction()
    {
        // action body
    }


}
