<?php

declare(strict_types=1);

namespace App\Entity\Quiz;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\PublicIdTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Entity\User;
use App\Entity\UserQuestion;
use App\Repository\QuestionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class), ORM\Table(name: 'q_questions')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PUBLIC_ID', fields: ['publicId'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_QUESTION', fields: ['question'])]
#[ORM\Index(name: 'QUESTION_IDX', columns: ['question'])]
class Question
{
    use IdTrait;
    use PublicIdTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $question = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $language = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isShared = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'created_by', nullable: false)]
    private ?User $createdBy = null;

    /**
     * @var Collection<int, UserQuestion>
     */
    #[ORM\OneToMany(targetEntity: UserQuestion::class, mappedBy: 'question')]
    private Collection $userQuestion;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->userQuestion = new ArrayCollection();
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function isShared(): ?bool
    {
        return $this->isShared;
    }

    public function setIsShared(bool $isShared): static
    {
        $this->isShared = $isShared;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, UserQuestion>
     */
    public function getUserQuestion(): Collection
    {
        return $this->userQuestion;
    }

    public function addUserQuestion(UserQuestion $userQuestion): static
    {
        if (!$this->userQuestion->contains($userQuestion)) {
            $this->userQuestion->add($userQuestion);
            $userQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeUserQuestion(UserQuestion $userQuestion): static
    {
        if ($this->userQuestion->removeElement($userQuestion)) {
            // set the owning side to null (unless already changed)
            if ($userQuestion->getQuestion() === $this) {
                $userQuestion->setQuestion(null);
            }
        }

        return $this;
    }
}
