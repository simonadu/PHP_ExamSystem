<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="teacher")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exam", mappedBy="teacher")
     */
    private $exams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exam", mappedBy="student")
     */
    private $examStudents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentA", mappedBy="student")
     */
    private $studentAs;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->exams = new ArrayCollection();
        $this->examStudents = new ArrayCollection();
        $this->studentAs = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getLevel(): ?bool
    {
        return $this->level;
    }

    public function setLevel(bool $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        if ($this->getLevel()==1)
            return array('ROLE_ADMIN');
        else
            return array('ROLE_USER');
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setTeacher($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getTeacher() === $this) {
                $question->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exam[]
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): self
    {
        if (!$this->exams->contains($exam)) {
            $this->exams[] = $exam;
            $exam->setTeacher($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): self
    {
        if ($this->exams->contains($exam)) {
            $this->exams->removeElement($exam);
            // set the owning side to null (unless already changed)
            if ($exam->getTeacher() === $this) {
                $exam->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exam[]
     */
    public function getExamStudents(): Collection
    {
        return $this->examStudents;
    }

    public function addExamStudent(Exam $examStudent): self
    {
        if (!$this->examStudents->contains($examStudent)) {
            $this->examStudents[] = $examStudent;
            $examStudent->setStudent($this);
        }

        return $this;
    }

    public function removeExamStudent(Exam $examStudent): self
    {
        if ($this->examStudents->contains($examStudent)) {
            $this->examStudents->removeElement($examStudent);
            // set the owning side to null (unless already changed)
            if ($examStudent->getStudent() === $this) {
                $examStudent->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StudentA[]
     */
    public function getStudentAs(): Collection
    {
        return $this->studentAs;
    }

    public function addStudentA(StudentA $studentA): self
    {
        if (!$this->studentAs->contains($studentA)) {
            $this->studentAs[] = $studentA;
            $studentA->setStudent($this);
        }

        return $this;
    }

    public function removeStudentA(StudentA $studentA): self
    {
        if ($this->studentAs->contains($studentA)) {
            $this->studentAs->removeElement($studentA);
            // set the owning side to null (unless already changed)
            if ($studentA->getStudent() === $this) {
                $studentA->setStudent(null);
            }
        }

        return $this;
    }

}
