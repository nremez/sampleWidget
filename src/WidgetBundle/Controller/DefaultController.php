<?php

namespace WidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WidgetBundle\Entity\User;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {

        if (!$request->query->count())
        {
            throw new EmptyRequestException("Request params are empty");
        }


        $widget = $this->get("sampleWidget");

        $image = $widget->renderWidget(
            $request->get("user_hash"),
            $request->get("width"),
            $request->get("height"),
            $request->get("bg_color"),
            $request->get("text_color")
        );

        // use output buffering directly to browser
        ob_start();
        @imagejpeg($image);
        $image_str = ob_get_contents();
        ob_end_clean();

        return new Response(
            $image_str,
            200,
            array('Content-Type' => 'image/jpeg')
        );
    }


    /**
     * Create user for tests
     *
     * @return Response
     */
    public function createAction()
    {
        $user = new User();
        $user->setName('Test User');
        $user->setHash("5d9c68c6c50ed3d02a2fcf54f63993b6");
        $user->setIsActive("true");

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return new Response('Created user id '.$user->getId());
    }

}
