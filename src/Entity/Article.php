<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

use Carbon\Carbon;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use function Symfony\Component\String\u;
use ApiPlatform\Metadata\Link;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    description: 'A rare and valuable treasure.',
    shortName: 'Articles',
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Patch(),
    ],
    normalizationContext: [
        'groups' => ['article:read'],
    ],
    denormalizationContext: [
        'groups' => ['article:write'],
    ],
)]
#[ApiResource(
    uriTemplate: '/users/{user_id}/articles.{_format}',
    shortName: 'Article',
    operations: [new GetCollection()],
    uriVariables: [
        'user_id' => new Link(
            fromProperty: 'articles',
            fromClass: User::class,
        ),
    ],
    normalizationContext: [
        'groups' => ['treasure:read'],
    ],
)]
#[ApiFilter(BooleanFilter::class, properties: ['published'])]
#[ApiFilter(SearchFilter::class, properties: [
    'owner.username' => 'partial',
])]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[ORM\Column(length: 255)]
    #[Groups(['article:read', 'article:write', 'user:read', 'user:write'])]
    private ?string $titre = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['article:read', 'article:write', 'user:read', 'user:write'])]
    private ?string $image = null;

    #[Assert\NotBlank]
    // #[Assert\Length(min: 2, max: 50, maxMessage: 'Describe your loot in 50 chars or less')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['article:read', 'article:write', 'user:read', 'user:write'])]
    private ?string $body = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['article:read', 'article:write', 'user:read', 'user:write'])]
    private ?bool $published = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdat = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'articles')]
    private Collection $Tag;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?User $owner = null;

    public function __construct()
    {
        $this->Tag = new ArrayCollection();
        $this->setcreatedat(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getImageUrl(): ?string
    {
        if (!$this->image) {
            return null;
        }
        if (strpos($this->image, '/') !== false) {
            return $this->image;
        }
        return sprintf('/uploads/article/%s', $this->image);
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(?\DateTimeInterface $createdat): static
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * A human-readable representation of when this treasure was plundered.
     */
    public function getCreatedatAgo(): string
    {
        return Carbon::instance($this->createdat)->diffForHumans();
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTag(): Collection
    {
        return $this->Tag;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->Tag->contains($tag)) {
            $this->Tag->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->Tag->removeElement($tag);

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
    public function __toString(): string
    {
      return $this->titre;   
    }
}
