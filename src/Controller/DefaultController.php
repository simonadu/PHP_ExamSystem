<?php

namespace App\Controller;
use App\Entity\Answer;
use App\Entity\Exam;
use App\Entity\Field;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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

    public function createQuestion (Request $request)
    {
        $newQuestion = new Question();
        $form = $this->createFormBuilder($newQuestion)
            ->add('field', EntityType::class, array('class'=> Field::class, 'choice_label' => 'name'))
            ->add('name', TextType::class, array('label' => 'Question'))
            ->add('save', SubmitType::class, array('label' => 'Add'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $newQuestion = $form->getData();
            $newQuestion->setTeacher($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newQuestion);
            $entityManager->flush();

            return $this->redirectToRoute('createQuestion');
        }

        $questions = $this->getUser()->getQuestions();
        return $this->render('createQuestion.html.twig',
            array('questions' => $questions,
                'addQuestionForm' => $form->createView()));
    }

    public function createAnswer (Request $request,$qId)
    {
        $newAnswer = new Answer();
        $form = $this->createFormBuilder($newAnswer)
            ->add('name', TextType::class, array('label' => 'Answer'))
            ->add('correct', CheckboxType::class, array(
                'label'    => 'Is this answer correct?',
                'required' => false,))
            ->add('save', SubmitType::class, array('label' => 'Add new'))
            ->getForm();
        $form->handleRequest($request);

        $question= $this->getDoctrine()->getRepository(Question::class)->find($qId);

        if($form->isSubmitted() && $form->isValid())
        {
            $newAnswer = $form->getData();
            $newAnswer->setQuestion($question);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newAnswer);
            $entityManager->flush();

            return $this->redirectToRoute('createAnswer', array('qId' => $qId));
        }

        $answers=$question->getAnswers();
        return $this->render('createAnswer.html.twig',
            array('answers' => $answers,
                'addAnswerForm' => $form->createView()));
    }

    public function createExam (Request $request)
    {
        $newExam = new Exam();
        $form = $this->createFormBuilder($newExam)
            ->add('field', EntityType::class, array('class'=> Field::class, 'choice_label' => 'name'))
            ->add('name', TextType::class, array('label' => 'Exam description'))
            ->add('save', SubmitType::class, array('label' => 'Add'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $newExam = $form->getData();
            $newExam->setTeacher($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newExam);
            $entityManager->flush();

            return $this->redirectToRoute('createExam');
        }

        $exams = $this->getUser()->getExams();
        return $this->render('createExam.html.twig',
            array('exams' => $exams,
                'addExamForm' => $form->createView()));
    }

}