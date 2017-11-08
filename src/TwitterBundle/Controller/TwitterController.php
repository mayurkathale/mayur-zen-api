<?php

namespace TwitterBundle\Controller;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use TwitterBundle\Entity\History;
use TwitterBundle\Helper\BaseRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
class TwitterController extends BaseRestController
{
    /**
     * @description This functions gets both of OAuth Access token and returns user data object.
     * @request POST
     * @author     Mayur Kathale <mayur.kathale@gmail.com
     * @return array
     * @Rest\Post("/user")
     * @Rest\View
     */
    public function userAction(Request $request) {
        return $this->_getUserData(
            $request->request->get('oauth_token'),
            $request->request->get('oauth_token_secret'));
    }

    /**
     * @description This functions returns initial authorization token which will be used further for authentication
     * @request GET
     * @author     Mayur Kathale <mayur.kathale@gmail.com
     * @return array
     * @Rest\Get("/auth")
     * @Rest\View
     */
    public function authAction()
    {
        return $this->_auth();
    }

    /**
     * @description Returns access token
     * @request POST
     * @author     Mayur Kathale <mayur.kathale@gmail.com
     * @return Permenent Access token
     * @Rest\Post("/accesstoken")
     * @Rest\View
     */
    public function accessTokenAction(Request $request)
    {
       return $this->_getAccessToken(
            $request->request->get('oauth_token'),
            $request->request->get('oauth_token_secret'),
            $request->request->get('oauth_verifier'));
    }

    /**
     * @return array
     * @Rest\Get("/searchtweets")
     * @Rest\View
     */
    public function searchTweetsAction(Request $request)
    {
        return $this->_searchTweets(
            $request->query->get('oauth_token'),
            $request->query->get('oauth_token_secret'),
            $request->query->get('query'),
            $request->query->get('latlong'),
            $request->query->get('count'));
    }

    /**
     * @return array
     * @Rest\Get("/history")
     * @Rest\View
     */
    public function getHistoryAction(Request $request)
    {
        return $this->getDoctrine()->getRepository('TwitterBundle:History')->findAll();
    }

    /**
     * @return array
     * @Rest\Post("/history")
     * @Rest\View
     */
    public function saveHistoryAction(Request $request)
    {
        $field = $request->request->get('searchfield');
        $user = $this->getDoctrine()
            ->getRepository('TwitterBundle:History')
            ->findOneBy(array('field' => $field));
        if(!$user) {
            $em = $this->getDoctrine()->getManager();
            $history = new History();
            $history->setField($field);
            $em->persist($history);
            $em->flush();
        }
        return array('status' => 'success');
    }
}
