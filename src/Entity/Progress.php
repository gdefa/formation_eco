<?php

namespace App\Entity;

use App\Repository\ProgressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @return mixed
     */
    public function getFormationFinished()
    {
        return $this->formationFinished;
    }

    /**
     * @param mixed $formationFinished
     * @return Progress
     */
    public function setFormationFinished($formationFinished)
    {
        $this->formationFinished = $formationFinished;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLessonFinished()
    {
        return $this->lessonFinished;
    }

    /**
     * @param mixed $lessonFinished
     * @return Progress
     */
    public function setLessonFinished($lessonFinished)
    {
        $this->lessonFinished = $lessonFinished;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUser(): ArrayCollection
    {
        return $this->user;
    }

    /**
     * @param ArrayCollection $user
     * @return Progress
     */
    public function setUser(ArrayCollection $user): Progress
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFormation(): ArrayCollection
    {
        return $this->formation;
    }

    /**
     * @param ArrayCollection $formation
     * @return Progress
     */
    public function setFormation(ArrayCollection $formation): Progress
    {
        $this->formation = $formation;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLesson(): ArrayCollection
    {
        return $this->lesson;
    }

    /**
     * @param ArrayCollection $lesson
     * @return Progress
     */
    public function setLesson(ArrayCollection $lesson): Progress
    {
        $this->lesson = $lesson;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProgressFormation()
    {
        return $this->progress_formation;
    }

    /**
     * @param mixed $progress_formation
     * @return Progress
     */
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
