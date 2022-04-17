<?php

namespace App\Entity;

use App\Repository\ProgressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgressRepository::class)]
class Progress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $formationFinished;

    #[ORM\Column(type: 'boolean')]
    private $lessonFinished;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'progress')]
    private $user;

    #[ORM\ManyToOne(targetEntity: formation::class, inversedBy: 'progress')]
    private $formation;

    #[ORM\ManyToOne(targetEntity: lesson::class, inversedBy: 'progress')]
    private $lesson;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $progress_formation;


    public function getFormationFinished()
    {
        return $this->formationFinished;
    }

    public function setFormationFinished($formationFinished)
    {
        $this->formationFinished = $formationFinished;
        return $this;
    }

    public function getLessonFinished()
    {
        return $this->lessonFinished;
    }

    public function setLessonFinished($lessonFinished)
    {
        $this->lessonFinished = $lessonFinished;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }


    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;
        return $this;
    }


    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }


    public function setLesson(?Lesson $lesson): self
    {
        $this->lesson = $lesson;
        return $this;
    }


    public function getProgressFormation($progress_formation)
    {
        return $this->progress_formation;
    }


    public function setProgressFormation($progress_formation)
    {
        $this->progress_formation = $progress_formation;
        return $this;
    }

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->formation = new ArrayCollection();
        $this->lesson = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
