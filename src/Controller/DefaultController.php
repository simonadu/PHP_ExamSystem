<?php

namespace App\Controller;
use App\Entity\Answer;
use App\Entity\Exam;
use App\Entity\Field;
use App\Entity\Question;
use App\Entity\StudentA;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

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


    public function levelControl()
    {
        $isTeacher = $this->getUser()->getLevel();

        if ($isTeacher == true)
            return $this->redirectToRoute('teacher');
        else
            return $this->redirectToRoute('student');
    }

    public function createQuestion(Request $request)
    {
        $newQuestion = new Question();
        $form = $this->createFormBuilder($newQuestion)
            ->add('field', EntityType::class, array(
                'class' => Field::class,
                'choice_label' => 'name'))
            ->add('name', TextType::class, array('label' => 'Question'))
            ->add('save', SubmitType::class, array('label' => 'Add'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
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

    public function createAnswer(Request $request, $qId)
    {
        $newAnswer = new Answer();
        $form = $this->createFormBuilder($newAnswer)
            ->add('name', TextType::class, array('label' => 'Answer'))
            ->add('correct', CheckboxType::class, array(
                'label' => 'Is this answer correct?',
                'required' => false,))
            ->add('save', SubmitType::class, array('label' => 'Add new'))
            ->getForm();
        $form->handleRequest($request);

        $question = $this->getDoctrine()->getRepository(Question::class)->find($qId);

        if ($form->isSubmitted() && $form->isValid()) {
            $newAnswer = $form->getData();
            $newAnswer->setQuestion($question);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newAnswer);
            $entityManager->flush();

            return $this->redirectToRoute('createAnswer', array('qId' => $qId));
        }

        $answers = $question->getAnswers();
        return $this->render('createAnswer.html.twig',
            array('answers' => $answers,
                'addAnswerForm' => $form->createView()));
    }

    public function createExam(Request $request)
    {
        $newExam = new Exam();
        $form = $this->createFormBuilder($newExam)
            ->add('field', EntityType::class,
                array('class' => Field::class,
                    'choice_label' => 'name'))
            ->add('name', TextType::class,
                array('label' => 'Exam description'))
            ->add('save', SubmitType::class, array('label' => 'Add'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
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

    public function examQuestion(Request $request, $eId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $students = $this->getDoctrine()->getRepository(User::class)->findBy(array('level' => 0));

        $form = $this->createFormBuilder($exam)
            ->add('questions', EntityType::class, array(
                'class' => Question::class, 'choice_label' => 'name',
                'choices' => $exam->getField()->getQuestions(),
                'expanded' => true, 'multiple' => true,
            ))
            ->add('student', EntityType::class,
                array(
                    'choices' => $students,
                    'placeholder' => 'All',
                    'required' => false,
                    'label' => 'Exam for',
                    'class' => User::class,
                    'choice_label' => 'username'))
            ->add('save', SubmitType::class, array('label' => 'Add'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $exam = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exam);
            $entityManager->flush();

            return $this->redirectToRoute('createExam');
        }

        return $this->render('examQuestion.html.twig',
            array('questions' => $exam,
                'addQuestionForm' => $form->createView())
        );
    }

    public function student()
    {
        $exams = $this->getUser()->getExamStudents();
        #     $examsNull= $this->getDoctrine()->getRepository(Exam::class)->findBy('student'=> 'null');

        return $this->render('student.html.twig',
            array('exams' => $exams));
    }

    public function takeExam($eId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $questions = $exam->getQuestions();

        return $this->render('takeExam.html.twig',
            array('questions' => $questions,
                'exam'=>$exam)
        );
    }

    public function submitAnswers(Request $request, $eId)
    {

        $request = Request::createFromGlobals()->request->all();
        $exam= $this->getDoctrine()->getRepository(Exam::class)->find($eId);


        foreach ($request as $questionId => $answerId){

            $question= $this->getDoctrine()->getRepository(Question::class)->find($questionId);
            $answer= $this->getDoctrine()->getRepository(Answer::class)->find($answerId);

            $studentAnswer= new StudentA();
            $studentAnswer->setStudent($this->getUser());
            $studentAnswer->setAnswer($answer);
            $studentAnswer->setExam($exam);
            $studentAnswer->setQuestion($question);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($studentAnswer);
        }

            $entityManager->flush();


        return $this->render('SResult.html.twig');

    }


/**
   public function randomSelection(ObjectManager $manager, Request $request, $eId)
    {

        $number = $request->getContent();
        $exam= $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $choices= [$exam->getField()->getQuestions()];
        shuffle($choices);
        for($i = 0; $i < $number; $i++)
        {
            $exam->addQuestion($choices[$i]);
            $manager->persist($exam);
        }
        $manager->flush();

        return $this->render('randomQuestion.html.twig');
    }
   */


}