<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CourseController extends Controller
{
    /**
     * @Route("/courses", name="courses")
     */
    public function indexAction()
    {
        return $this->render('course/index.html.twig');
    }

    public function searchAction()
    {
        return $this->render('course/search.html.twig');
    }
}