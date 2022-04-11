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

    #[ORM\ManyToMany(targetEntity: user::class, inversedBy: 'progress')]
    private $user;

    #[ORM\ManyToMany(targetEntity: formation::class, inversedBy: 'progress')]
    private $formation;

    #[ORM\ManyToMany(targetEntity: lesson::class, inversedBy: 'progress')]
    private $lesson;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $progress_formation;

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

    public function getFormationFinished(): ?string
    {
        return $this->formationFinished;
    }

    public function setFormationFinished(string $formationFinished): self
    {
        $this->formationFinished = $formationFinished;

        return $this;
    }


    public function getLessonFinished(): ?bool
    {
        return $this->lessonFinished;
    }

    public function setLessonFinished(bool $lessonFinished): self
    {
        $this->lessonFinished = $lessonFinished;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(user $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(user $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, formation>
     */
    public function getFormation(): Collection
    {
        return $this->formation;
    }

    public function addFormation(formation $formation): self
    {
        if (!$this->formation->contains($formation)) {
            $this->formation[] = $formation;
        }

        return $this;
    }

    public function removeFormation(formation $formation): self
    {
        $this->formation->removeElement($formation);

        return $this;
    }

    /**
     * @return Collection<int, section>
     */
    public function getSection(): Collection
    {
        return $this->section;
    }

    public function addSection(section $section): self
    {
        if (!$this->section->contains($section)) {
            $this->section[] = $section;
        }

        return $this;
    }

    public function removeSection(section $section): self
    {
        $this->section->removeElement($section);

        return $this;
    }

    /**
     * @return Collection<int, lesson>
     */
    public function getLesson(): Collection
    {
        return $this->lesson;
    }

    public function addLesson(lesson $lesson): self
    {
        if (!$this->lesson->contains($lesson)) {
            $this->lesson[] = $lesson;
        }

        return $this;
    }

    public function removeLesson(lesson $lesson): self
    {
        $this->lesson->removeElement($lesson);

        return $this;
    }

    public function getProgressFormation(): ?int
    {
        return $this->progress_formation;
    }

    public function setProgressFormation(?int $progress_formation): self
    {
        $this->progress_formation = $progress_formation;

        return $this;
    }

    public function getFormationProgress(): ?int
    {
        return $this->progress_formation;
    }

    public function setFormationProgress(?int $formation_progress): self
    {
        $this->progress_formation = $formation_progress;

        return $this;
    }

}
