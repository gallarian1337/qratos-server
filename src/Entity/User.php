<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Quiz\Question;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\PublicIdTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class), ORM\Table(name: 'q_users')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PUBLIC_ID', fields: ['publicId'])]
#[ORM\Index(name: 'NICKNAME_IDX', columns: ['nickname'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use PublicIdTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 25)]
    private ?string $nickname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $platform = 'local';

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $accessToken = null;

    // date d'expiration de l'accessToken
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $expireAt = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private ?bool $isActive = true;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isBan = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isSubscription = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isDeleted = false;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'createdBy')]
    private Collection $questions;

    /**
     * @var Collection<int, UserQuestion>
     */
    #[ORM\OneToMany(targetEntity: UserQuestion::class, mappedBy: 'user')]
    private Collection $userQuestion;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->questions = new ArrayCollection();
        $this->userQuestion = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->nickname;
    }

    public function getRole(): string
    {
        return $this->roles[0];
    }

    public function setRole(string $role): static
    {
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): static
    {
        $this->platform = $platform;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getExpireAt(): ?DateTimeImmutable
    {
        return $this->expireAt;
    }

    public function setExpireAt(?DateTimeImmutable $expireAt): static
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isBan(): ?bool
    {
        return $this->isBan;
    }

    public function setIsBan(bool $isBan): static
    {
        $this->isBan = $isBan;

        return $this;
    }

    public function isSubscription(): ?bool
    {
        return $this->isSubscription;
    }

    public function setIsSubscription(bool $isSubscription): static
    {
        $this->isSubscription = $isSubscription;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setCreatedBy($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getCreatedBy() === $this) {
                $question->setCreatedBy(null);
            }
        }

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
            $userQuestion->setUser($this);
        }

        return $this;
    }

    public function removeUserQuestion(UserQuestion $userQuestion): static
    {
        if ($this->userQuestion->removeElement($userQuestion)) {
            // set the owning side to null (unless already changed)
            if ($userQuestion->getUser() === $this) {
                $userQuestion->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }
}
