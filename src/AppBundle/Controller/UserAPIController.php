<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\User;
use AppBundle\Entity\Group;

class UserAPIController extends Controller
{
    /**
     * Create a user
     * @Route("/userapi/createuser")
     */
    public function createuserAction(Request $request)
    {
        $name = $request->query->get('name');
        $email = $request->query->get('email');

        if (!isset($name)) {
            return new JsonResponse(array('response'=>'name is not specified'));
        }

        if (trim($name)=='') {
            return new JsonResponse(array('response'=>'name cannot be blank'));
        }

        if (!isset($email)) {
            return new JsonResponse(array('response'=>'email is not specified'));
        }

        if (trim($email)=='') {
            return new JsonResponse(array('response'=>'email cannot be blank'));
        }

        $user = new User;
        $user->setName(htmlspecialchars($name));   // enclose in htmlspecialchars() ???
        $user->setEmail(htmlspecialchars($email));

        $UserEntityMan = $this->getDoctrine()->getEntityManager();
        $UserEntityMan->persist($user);
        $UserEntityMan->flush();

        return new JsonResponse(array('response'=>'ok '.$user->getId()));
    }

    /**
     * list all users
     * @Route("/userapi/listuser")
     */
    public function listuserAction(Request $request)
    {
        $result = array('Users'=>array());

        $UserEntityMan = $this->getDoctrine()->getEntityManager();
        $users = $UserEntityMan->getRepository('AppBundle:User')->findAll();
        foreach ($users as $u) {
            $r = array_push($result['Users'], array('id'=>$u->getId(), 
                                                    'name'=>$u->getName(), 
                                                    'email'=>$u->getEmail(), 
                                                    'groups'=>array()
                                                   )
                           );
            foreach ($u->groups as $g) {
                array_push($result['Users'][$r-1]['groups'], $g->getId() //array('id'=>$g->getId())
                          );
            }   
        }
        return new JsonResponse($result);
    }

    /**
     * common method to modify user, for both modify actions
     */
    private function modifyuserinternal(int $uid, Request $request)
    {
        $name = $request->query->get('name');
        $email = $request->query->get('email');

        $UserEntityMan = $this->getDoctrine()->getEntityManager();
        $user = $UserEntityMan->getRepository('AppBundle:User')->find($uid);

        if (!$user) {
            return new JsonResponse(array('response'=>'User not found'));
        }

        if (isset($name)) {
            $user->setName(htmlspecialchars($name));
        }

        if (isset($email)) {
            $user->setEmail(htmlspecialchars($email));
        }

        $UserEntityMan->persist($user);
        $UserEntityMan->flush();

        return new JsonResponse(array('response'=>'ok'));
    }

    /**
     * modify a user,  action for request ../modifyuser?id=..&..
     * @Route("/userapi/modifyuseroldstyle")
     */
    public function modifyuseroldstyleAction(Request $request)
    {
        $uid = $request->query->get('id');;
        if (!isset($uid)) {
            return new JsonResponse(array('response'=>"User's ID is not specified"));
        } 
        return $this->modifyuserinternal($uid, $request);
    }

    
    /**
     * modify a user, action for request ../modifyuser/{id}?..
     * @Route("/userapi/modifyuser/{uid}")
     */
    public function modifyuserAction(int $uid, Request $request)
    {
        return $this->modifyuserinternal($uid, $request);
    }

    /**
     * Show user's properties
     * @Route("/userapi/showuser/{uid}")
     */
    public function showuserAction(int $uid)
    {
        $UserEntityMan = $this->getDoctrine()->getEntityManager();
        $user = $UserEntityMan->getRepository('AppBundle:User')->find($uid);

        if (!$user) {
            return new JsonResponse(array('response'=>'User not found'));
        }

        $result = array('id'=>$user->getId(), 
                        'name'=>$user->getName(), 
                        'email'=>$user->getEmail(), 
                        'groups'=>array()
                       );
        foreach ($user->groups as $g) {
            array_push($result['groups'], $g->getId() //array('id'=>$g->getId())
                      );
        }   
        return new JsonResponse($result);
    }

    /**
     * Delete a user
     * @Route("/userapi/deleteuser/{uid}")
     */
    public function deleteuserAction(int $uid)
    {
        $UserEntityMan = $this->getDoctrine()->getEntityManager();
        $user = $UserEntityMan->getRepository('AppBundle:User')->find($uid);

        if (!$user) {
            return new JsonResponse(array('response'=>'User not found'));
        }

        $UserEntityMan->remove($user);
        $UserEntityMan->flush();

        return new JsonResponse(array('response'=>'ok'));
    }

    /**
     * Add a group to user
     * @Route("/userapi/addgrouptouser/{uid}/{gid}")
     */
    public function addgrouptouserAction(int $uid, int $gid)
    {
        $ReturnResult = '';

        $UserEntityMan = $this->getDoctrine()->getEntityManager();
        $user = $UserEntityMan->getRepository('AppBundle:User')->find($uid);

        if (!$user) {
            return new JsonResponse(array('response'=>'User not found'));
        }

        $GroupEntityMan = $this->getDoctrine()->getEntityManager();
        $group = $GroupEntityMan->getRepository('AppBundle:Group')->find($gid);

        if (!$group) {
            return new JsonResponse(array('response'=>'Group not found'));
        }


        if (!$user->groups->contains($group)) {
            $user->groups->add($group);
            $UserEntityMan->persist($user);
            $UserEntityMan->flush();
            $ReturnResult = 'ok';
        } else {
            $ReturnResult = "ok, but group already exists in user's groups";
        }

        return new JsonResponse(array('response'=>$ReturnResult));
    }


    /**
     * Delete a group from user
     * @Route("/userapi/deletegroupfromuser/{uid}/{gid}")
     */
    public function deletegroupfromuserAction(int $uid, int $gid)
    {
        $ReturnResult = '';

        $UserEntityMan = $this->getDoctrine()->getEntityManager();
        $user = $UserEntityMan->getRepository('AppBundle:User')->find($uid);

        if (!$user) {
            return new JsonResponse(array('response'=>'User not found'));
        }

        $GroupEntityMan = $this->getDoctrine()->getEntityManager();
        $group = $GroupEntityMan->getRepository('AppBundle:Group')->find($gid);

        if (!$group) {
            return new JsonResponse(array('response'=>'Group not found'));
        }

        if ($user->groups->removeElement($group)) {
            $UserEntityMan->persist($user);
            $UserEntityMan->flush();
            $ReturnResult = 'ok';
        } else {
            $ReturnResult = "ok, but group doesn't exist in user's groups";
        }

        return new JsonResponse(array('response'=>$ReturnResult));
    }


    /**
     * Report
     * @Route("/userapi/report")
     */
    public function reportAction()
    {
        $result = array('Groups'=>array());

        $GroupEntityMan = $this->getDoctrine()->getEntityManager();
        $groups = $GroupEntityMan->getRepository('AppBundle:Group')->findAll();

        foreach ($groups as $g) {
            $r = array_push($result['Groups'], array('id'=>$g->getId(), 
                                                     'name'=>$g->getName(),
                                                     'users'=>array()
                                                    )
                           );
            foreach ($g->users as $u) {
                array_push($result['Groups'][$r-1]['users'], array('id'=>$u->getId(),
                                                                   'name'=>$u->getName()
                                                                  )
                          );
            }   
        }
        return new JsonResponse($result);
    }

}
