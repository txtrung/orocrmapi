<?php

namespace MCM\DemoBundle\Controller;

use MCM\DemoBundle\Entity\SimpleCrud;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\BrowserKit\Request;

class DefaultController extends FOSRestController
{
    public function indexAction($name)
    {
        return $this->render('MCMDemoBundle:Default:index.html.twig');
//        return $this->render('MCMDemoBundle:Default:index.html.twig', array('name' => $name));
    }

    public function allAction()
    {

        $users = $this->getDoctrine()
            ->getRepository('MCMDemoBundle:SimpleCrud')
            ->findAll();

        return array($users);
    }

    public function getAction($id)
    {

        $user = $this->getDoctrine()
            ->getRepository('MCMDemoBundle:SimpleCrud')
            -> findOneById($id);

        if (!$user)
        {
            throw $this->createNotFoundException(
                'No user'
            );
        }

        return array($user);
    }

    public function newAction()
    {

        $content = $this->get('request')->getContent();

        if (!empty($content))
        {
            $params = json_decode($content, true);
        }
        else { return array("success"=>0); }

        for ($i = 0;$i<count($params);$i++)
        {
            $user = new SimpleCrud();
            $user->setFirstname($params[$i]["firstname"]);
            $user->setLastname($params[$i]["lastname"]);
            $user->setEmail($params[$i]["email"]);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);

            $em->flush();
        }

        return array('aaa'=>'success!');
    }

    public function updateAction($update)
    {
        $content = $this -> get('request') -> getContent();

        if(!empty($content))
        {
            $params = json_decode($content,true);
        }
        else
        {
            return array('success'=>'0');
        }

        for($i = 0; $i < count($params); $i++)
        {
            $user = new SimpleCrud();
            $user -> setId($params[$i]['id'])
                  -> setFirstname($params[$i]['firstname'])
                  -> setLastname($params[$i]['lastname'])
                  -> setEmail($params[$i]['email']);

            $em = $this->getDoctrine()->getManager();
            $userUpdate = $em->getRepository('MCMDemoBundle:SimpleCrud')->find($user->getId());

            if (!$userUpdate) {
                return array('user'=>'not found');
            }

            if(!empty($user->getFirstname()))
            {
                $userUpdate -> setFirstname($user->getFirstname());
            }
            if(!empty($user->getLastname()))
            {
                $userUpdate -> setLastname($user->getLastname());
            }
            if(!empty($user->getEmail()))
            {
                $userUpdate -> setEmail($user->getEmail());
            }

            $em->flush();
        }

        return array('update'=>'success');
    }
}
