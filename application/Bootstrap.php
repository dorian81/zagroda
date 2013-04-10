<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
  protected function _initSession() 
    { 
            Zend_Session::start(); 
    } 

    protected function _initFrontController() {
        // The Zend_Front_Controller class implements the Singleton pattern
        $frontController = Zend_Controller_Front::getInstance();

	$frontController->setControllerDirectory('../application/controllers');
	$frontController->addControllerDirectory('../modules/console/controllers', 'console');

        // look in the modules directory and automatically make modules out of all folders found
        $frontController->addModuleDirectory(APPLICATION_PATH . '/modules');

        // forces the front controller to forward all errors to the default error controller (may already be false by default)
        $frontController->throwExceptions(false);

        return $frontController;
    }

}

