<?php
namespace stack\web\module;
/*
 * Copyright (C) 2012 Michael Saller
 * Licensed under MIT License, see /path/to/stack/LICENSE
 */

class Login extends \stack\module\BaseModule {

    const NAME = 'stack.web.login';

    public function run(\stack\Context $context, \stack\web\Request $request) {
        if(!$request->isXHR()) {
            // initial request, show form
            $template = new \lean\Template(STACK_ROOT . '/stack/template/login.php');
            $template->rootpw = '244be46c5860e5a5c50ab3b2c4896e84d7bd0f7e';
            $markup = $template->render();
            $response = new \stack\web\Response_HTML($markup);
        } else {
            // form has been sent via ajax.
            $context->pushSecurity(new \stack\security\PriviledgedSecurity());
            try {
                // read user and create response
                $userFile = $context->getShell()->readFile(\stack\Root::ROOT_PATH_USERS . '/' . $request->post('uName'));
                $user = $userFile->getModule();
                /**
                 * @var \stack\module\User $user
                 */

                // if user is actually auth'd, save it in session
                $loggedIn = $user->auth($request->post('uPass'));
                if($loggedIn) {
                    $context->setUser($request->post('uName'));
                    $response = new \stack\web\Response_JSON(['authorized' => true, 'home' => $user->getHome()]);
                }
                else {
                    $response = new \stack\web\Response_JSON(['authorized' => false]);
                }
            }
            catch(\stack\fileSystem\Exception_FileNotFound $e) {
                $response = new \stack\web\Response_JSON(['authorized' => false]);
            }
            $context->pullSecurity();
        }

        return $response;
    }
}