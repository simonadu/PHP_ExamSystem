<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig');
    }

    public function teacher()
    {
        return $this->render('teacher.html.twig');
    }

    public function student()
    {
        return $this->render('student.html.twig');
    }

    public function levelControl()
    {
        $isTeacher = $this->getUser()->getLevel();

        if ($isTeacher== true)
            return $this->redirectToRoute('teacher');
        else
            return $this->redirectToRoute('student');
    }



}