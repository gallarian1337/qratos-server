<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Quiz\Question;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\PublicIdTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\UserQuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserQuestionRepository::class), ORM\Table(name: 'q_questions_users')]
class UserQuestion
{
    use IdTrait;
    use PublicIdTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer', options: ['default' => 5])]
    private ?int $duration = 5;

    #[ORM\Column(type: 'float', options: ['default' => 1.0])]
    private ?float $numberOfPoint = 1.0;

    #[ORM\Column(type: 'json')]
    private array $goodAnswers = [];

    #[ORM\Column(type: 'json')]
    private array $badAnswers = [];

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userQuestion')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'userQuestion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getNumberOfPoint(): ?float
    {
        return $this->numberOfPoint;
    }

    public function setNumberOfPoint(float $numberOfPoint): static
    {
        $this->numberOfPoint = $numberOfPoint;

        return $this;
    }

    public function getGoodAnswers(): array
    {
        return $this->goodAnswers;
    }

    public function setGoodAnswers(array $goodAnswers): static
    {
        $this->goodAnswers = $goodAnswers;

        return $this;
    }

    public function getBadAnswers(): array
    {
        return $this->badAnswers;
    }

    public function setBadAnswers(array $badAnswers): static
    {
        $this->badAnswers = $badAnswers;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }
}
