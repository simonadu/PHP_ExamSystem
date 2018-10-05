<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Field", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $field;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question")
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Exam", mappedBy="questions")
     */
    private $exams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentA", mappedBy="question")
     */
    private $studentAs;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->exams = new ArrayCollection();
        $this->studentAs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?user
    {
        return $this->teacher;
    }

    public function setTeacher(?user $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getField(): ?field
    {
        return $this->field;
    }

    public function setField(?field $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
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
            $exam->addQuestion($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): self
    {
        if ($this->exams->contains($exam)) {
            $this->exams->removeElement($exam);
            $exam->removeQuestion($this);
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
            $studentA->setQuestion($this);
        }

        return $this;
    }

    public function removeStudentA(StudentA $studentA): self
    {
        if ($this->studentAs->contains($studentA)) {
            $this->studentAs->removeElement($studentA);
            // set the owning side to null (unless already changed)
            if ($studentA->getQuestion() === $this) {
                $studentA->setQuestion(null);
            }
        }

        return $this;
    }
}
