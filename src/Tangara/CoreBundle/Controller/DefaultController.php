<?php

namespace Tangara\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Tangara\CoreBundle\Entity\Project;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('TangaraCoreBundle:Default:homepage.html.twig');
    }

    //controleur vers la page de confirmation
    public function confirmationAction() {

        //get form
        $msg = $this->container->get('request')->get('object');
        $goupId = $this->container->get('request')->get('groups');

        $group = $this->getDoctrine()->getManager()
                ->getRepository('TangaraCoreBundle:Group')
                ->find($goupId);

        //get Group Leader, then user and mail
        $user = $group->getGroupsLeader();
        $leader_mail = $user->getEmail();


        //Send mail
        $message = \Swift_Message::newInstance()
                ->setSubject('Demande de rejoidre le groupe')
                ->setFrom('tangaraui@colombbus.org')
                ->setTo('tangaraui@colombbus.org') //TODO -> $leader_mail 
                ->setBody($msg)
        ;
        $this->container->get('mailer')->send($message);

        //return $this->render('TangaraCoreBundle:Project:confirmation.html.twig');
        return new Response('Message envoyé');
    }

    public function localeAction() {
        $request = $this->getRequest();
        $locale = $request->getLocale();

        if ($request) {
            $response = new JsonResponse();
            $response->setData(array(
                'locale' => $locale));
            return $response;
        }
    }

    public function ajaxAction() {
        return $this->render('TangaraCoreBundle:Default:ajax_symfony.html.twig');
    }

    public function sessionAction(Project $project) {
        
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('projectid', $project->getId());
        return $this->forward('TangaraCoreBundle:Project:create');
    }

}
