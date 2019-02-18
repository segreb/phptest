<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Group;

class GroupAPIController extends Controller
{
    /**
     * Create a group
     * @Route("/userapi/creategroup")
     */
    public function creategroupAction(Request $request)
    {
        $name = $request->query->get('name');

        if (!isset($name)) {
            return new JsonResponse(array('response'=>'name is not specified'));
        }

        if (trim($name)=='') {
            return new JsonResponse(array('response'=>'name cannot be blank'));
        }

        $group = new Group;
        $group->setName(htmlspecialchars($name));   // enclose in htmlspecialchars() ???

        $GroupEntityMan = $this->getDoctrine()->getEntityManager();
        $GroupEntityMan->persist($group);
        $GroupEntityMan->flush();

        return new JsonResponse(array('response'=>'ok '.$group->getId()));
    }

    /**
     * list all group
     * @Route("/userapi/listgroup")
     */
    public function listgroupAction(Request $request)
    {
        $result = array('Groups'=>array());
        $GroupEntityMan = $this->getDoctrine()->getEntityManager();
        $groups = $GroupEntityMan->getRepository('AppBundle:Group')->findAll();
        foreach ($groups as $g) {
            array_push($result['Groups'], array('id'=>$g->getId(), 
                                                'name'=>$g->getName()
                                               )
                      );
        }
        return new JsonResponse($result);
    }
    
    /**
     * modify a group
     * @Route("/userapi/modifygroup/{gid}")
     */
    public function modifygroupAction(int $gid, Request $request)
    {
        $name = $request->query->get('name');

        $GroupEntityMan = $this->getDoctrine()->getEntityManager();
        $group = $GroupEntityMan->getRepository('AppBundle:Group')->find($gid);

        if (!$group) {
            return new JsonResponse(array('response'=>'Group not found'));
        }

        if (isset($name)) {
            $group->setName(htmlspecialchars($name));
        }

        $GroupEntityMan->persist($group);
        $GroupEntityMan->flush();

        return new JsonResponse(array('response'=>'ok'));
    }

    /**
     * Show group's properties
     * @Route("/userapi/showgroup/{gid}")
     */
    public function showgroupAction(int $gid)
    {
        $GroupEntityMan = $this->getDoctrine()->getEntityManager();
        $group = $GroupEntityMan->getRepository('AppBundle:Group')->find($gid);

        if (!$group) {
            return new JsonResponse(array('response'=>'Group not found'));
        }

        $result = array('id'=>$group->getId(), 
                        'name'=>$group->getName()
                       );

        return new JsonResponse($result);
    }

    /**
     * Delete a group
     * @Route("/userapi/deletegroup/{gid}")
     */
    public function deletegroupAction(int $gid)
    {
        $GroupEntityMan = $this->getDoctrine()->getEntityManager();
        $group = $GroupEntityMan->getRepository('AppBundle:Group')->find($gid);

        if (!$group) {
            return new JsonResponse(array('response'=>'Group not found'));
        }
        $GroupEntityMan->remove($group);
        $GroupEntityMan->flush();

        return new JsonResponse(array('response'=>'ok'));
    }
}
