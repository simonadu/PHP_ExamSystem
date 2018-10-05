<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\question", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $correct;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentA", mappedBy="answer")
     */
    private $studentAs;

    public function __construct()
    {
        $this->studentAs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCorrect(): ?bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): self
    {
        $this->correct = $correct;

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
            $studentA->setAnswer($this);
        }

        return $this;
    }

    public function removeStudentA(StudentA $studentA): self
    {
        if ($this->studentAs->contains($studentA)) {
            $this->studentAs->removeElement($studentA);
            // set the owning side to null (unless already changed)
            if ($studentA->getAnswer() === $this) {
                $studentA->setAnswer(null);
            }
        }

        return $this;
    }
}
