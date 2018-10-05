<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentARepository")
 */
class StudentA
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="studentAs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\exam", inversedBy="studentAs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\question", inversedBy="studentAs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\answer", inversedBy="studentAs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?user
    {
        return $this->student;
    }

    public function setStudent(?user $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getExam(): ?exam
    {
        return $this->exam;
    }

    public function setExam(?exam $exam): self
    {
        $this->exam = $exam;

        return $this;
    }

    public function getQuestion(): ?question
    {
        return $this->question;
    }

    public function setQuestion(?question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?answer
    {
        return $this->answer;
    }

    public function setAnswer(?answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
