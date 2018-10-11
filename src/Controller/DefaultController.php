<?php

namespace App\Controller;
use App\Entity\Answer;
use App\Entity\Exam;
use App\Entity\ExamsForAll;
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
                'question'=> $question,
                'addAnswerForm' => $form->createView()));
    }

    public function createExam(Request $request)
    {
        $newExam = new Exam();
        $students = $this->getDoctrine()->getRepository(User::class)->findBy(array('level' => 0));
        $form = $this->createFormBuilder($newExam)
            ->add('field', EntityType::class,
                array('class' => Field::class,
                    'choice_label' => 'name'))
            ->add('name', TextType::class,
                array('label' => 'Exam description'))
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
            $newExam = $form->getData();
            if ($newExam->getStudent()==null)
            {
                foreach ($students as $student)
                {
                    $all= new ExamsForAll();
                    $all->setExam($newExam);
                    $all->setStudent($student);
                    $all->setStatus(false);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($all);
                }
            }
            $newExam->setTeacher($this->getUser());
            $newExam->setStatus(false);
            $newExam->setResult(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newExam);
            $entityManager->flush();

            return $this->redirectToRoute('createExam');
        }

        $exams = $this->getUser()->getExams();
        $questions= $this->getDoctrine()->getRepository(Question::class)->findBy(
            ['teacher'=> $this->getUser()]);
        return $this->render('createExam.html.twig',
            array('exams' => $exams,
                'questions'=>$questions,
                'addExamForm' => $form->createView()));
    }

    public function randomExam($eId)
    {
        $exam= $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $field= $exam->getField();
        $questions= $this->getDoctrine()->getRepository(Question::class)->findBy(
            ['teacher'=> $this->getUser(),
                'field'=>$field]);

        return $this->render('randomExam.html.twig',
            array('exam' => $exam,
                'questions'=>$questions));
    }


    public function randomSelection(ObjectManager $manager, Request $request, $eId)
    {
        $number = (int)Request::createFromGlobals()->request->get("number");

        $exam= $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $questions= $exam->getField()->getQuestions();
        $choices=[];
        $i=0;
        foreach ($questions as $question)
        {
            $choices[$i]=$question->getId();
            $i++;
        }
        shuffle($choices);

        for($i = 0; $i < $number; $i++)
        {
            $q= $this->getDoctrine()->getRepository(Question::class)->find($choices[$i]);
            $exam->addQuestion($q);
            $manager->persist($exam);
        }
        $manager->flush();

        return $this->render('randomQuestion.html.twig', array ('number'=> $number));
    }

    public function examQuestion(Request $request, $eId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($eId);

        $form = $this->createFormBuilder($exam)
            ->add('questions', EntityType::class,
                array(
                'label'=> false,
                'class' => Question::class, 'choice_label' => 'name',
                'choices' => $exam->getField()->getQuestions(),
                'expanded' => true, 'multiple' => true,
            ))
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
                'exam'=>$exam,
                'addQuestionForm' => $form->createView())
        );
    }
    
    public function viewQuestion($eId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $questions= $exam->getQuestions();

        return $this->render('viewQuestion.html.twig',
            array('questions' => $questions,
                'exam' => $exam));
    }

    public function student()
    {
        $exams = $this->getUser()->getExamStudents();
        #     $examsNull= $this->getDoctrine()->getRepository(Exam::class)->findBy('student'=> 'null');

        $tests= $this->getUser()->getExamsForAlls();

        return $this->render('student.html.twig',
            array('exams' => $exams,
                'tests'=>$tests));
    }

    public function takeExam($eId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $entityManager = $this->getDoctrine()->getManager();
        $exam->setStatus(true);
        $entityManager->flush();

        $questions = $exam->getQuestions();

        return $this->render('takeExam.html.twig',
            array('questions' => $questions,
                'exam'=>$exam)
        );

    }

    public function takeAllExam($tId)
    {
        $exam = $this->getDoctrine()->getRepository(ExamsForAll::class)->find($tId);
        $entityManager = $this->getDoctrine()->getManager();
        $exam->setStatus(true);
        $entityManager->flush();

        $questions = $exam->getExam()->getQuestions();

        return $this->render('takeAllExam.html.twig',
            array('questions' => $questions,
                'exam'=>$exam));
    }

    public function submitAnswers(Request $request, $eId)
    {

        $request = Request::createFromGlobals()->request->all();
        $exam= $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $result=('0');
        $count=('0');
        $entityManager = $this->getDoctrine()->getManager();



        foreach ($request as $questionId => $answerId){

            $question= $this->getDoctrine()->getRepository(Question::class)->find($questionId);
            $answer= $this->getDoctrine()->getRepository(Answer::class)->find($answerId);

            $studentAnswer= new StudentA();
            $studentAnswer->setStudent($this->getUser());
            $studentAnswer->setAnswer($answer);
            $correct= $answer->getCorrect();
            if ($correct==true) $result++;
            $studentAnswer->setExam($exam);
            $studentAnswer->setQuestion($question);
            $entityManager->persist($studentAnswer);
            $count++;
        }

        $entityManager->flush();
        $entityManager = $this->getDoctrine()->getManager();
        $exam->setResult($result/$count);
        $entityManager->flush();

        return $this->render('submited.html.twig');

    }

    public function submitAllAnswers(Request $request, $eId)
    {
        $request = Request::createFromGlobals()->request->all();
        $exam= $this->getDoctrine()->getRepository(ExamsForAll::class)->find($eId);
        $result=('0');
        $count=('0');
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($request as $questionId => $answerId){

            $question= $this->getDoctrine()->getRepository(Question::class)->find($questionId);
            $answer= $this->getDoctrine()->getRepository(Answer::class)->find($answerId);

            $studentAnswer= new StudentA();
            $studentAnswer->setStudent($this->getUser());
            $studentAnswer->setAnswer($answer);
            $correct= $answer->getCorrect();
            if ($correct==true) $result++;
            $studentAnswer->setExam($exam->getExam());
            $studentAnswer->setQuestion($question);
            $entityManager->persist($studentAnswer);
            $count++;
            $entityManager->flush();
        }

        $entityManager->flush();
        $entityManager = $this->getDoctrine()->getManager();
        $exam->setResult($result/$count);
        $entityManager->flush();

        return $this->render('submited.html.twig');

    }


    public function teacherResult()
    {
        $teacher= $this->getUser()->getId();
        $exams= $this->getDoctrine()->getRepository(Exam::class)->findBy(['teacher'=>$this->getUser()]);

        return $this->render('TResults.html.twig',
            array('exams' => $exams ));
    }

    public function detailedResults($eId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $exams= $this->getDoctrine()->getRepository(ExamsForAll::class)->findBy(['exam'=>$exam]);

        return $this->render('TAllResults.html.twig',
            array('exam'=>$exam,
                'exams' => $exams ));
    }

    public function viewResult($eId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($eId);
        $answers = $this->getDoctrine()->getRepository(StudentA::class)->findBy(['exam'=>$eId]);

        $isTeacher = $this->getUser()->getLevel();

        if ($isTeacher == true){
            return $this->render('TViewResult.html.twig',
            array('answers' => $answers,
                'exam' => $exam));
        }
        else {
            return $this->render('viewResult.html.twig',
                array('answers' => $answers,
                    'exam' => $exam));
        }
    }

    public function viewDetailedResult($eId)
    {
        $exam=$this->getDoctrine()->getRepository(ExamsForAll::class)->find($eId);
        $id= $exam->getExam();
        $answers = $this->getDoctrine()->getRepository(StudentA::class)->findBy(['exam'=>$id]);

        return $this->render('viewDetailedResult.html.twig',
            array('answers' => $answers, 'exam' => $exam));
    }



}