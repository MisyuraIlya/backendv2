<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Enum\CatalogDocumentTypeEnum;
use App\Repository\CategoryRepository;
use App\State\CategoriesDynamicProvider;
use App\State\CategoriesStateProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Link;

#[ApiResource(
    normalizationContext: ['groups' => ['category:read']],
    denormalizationContext: ['groups' => ['category:write']],
    order: ['orden' => 'ASC'],
    paginationItemsPerPage: 1000,
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/categoriesApp',
            provider: CategoriesStateProvider::class
        ),
        new GetCollection(
            uriTemplate: '/categoriesAppDynamic/{lvl1}/{lvl2}/{lvl3}',
            uriVariables: [
                'lvl1' => new Link(fromClass: Category::class),
                'lvl2' => new Link(fromClass: Category::class),
                'lvl3' => new Link(fromClass: Category::class),
            ],
            provider: CategoriesDynamicProvider::class,
        )
    ],
    normalizationContext: ['groups' => ['category:read']],
    denormalizationContext: ['groups' => ['category:write']],
    order: ['orden' => 'ASC']
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'lvlNumber' => 'exact',
    ]
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:read'])]
    private ?int $id = null;

    #[Groups(['product:read','category:read'])]
    #[ORM\Column(length: 255)]
    private ?string $extId = null;

    #[Groups(['product:read','category:read','category:write'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(['category:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Groups(['category:read','category:write'])]
    #[ORM\Column]
    private ?bool $isPublished = null;

    #[Groups(['category:read','category:write'])]
    #[ORM\Column(nullable: true)]
    private ?int $orden = null;

    #[Groups(['category:read'])]
    #[ORM\Column(nullable: true)]
    private ?int $lvlNumber = null;

    #[Groups(['category:read'])]
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    private ?self $parent = null;

    #[Groups(['category:read'])]
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $categories;



    #[Groups(['category:read','category:write'])]
    #[ORM\ManyToOne(inversedBy: 'categories')]
    private ?MediaObject $MediaObject = null;

    #[ORM\Column(length: 255)]
    #[Groups(['category:read'])]
    private ?string $identify = null;

    #[Groups(['category:read'])]
    #[ORM\Column]
    private ?bool $isBlockedForView = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->productsLvl1 = new ArrayCollection();
        $this->productsLvl2 = new ArrayCollection();
        $this->productsLvl3 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtId(): ?string
    {
        return $this->extId;
    }

    public function setExtId(string $extId): static
    {
        $this->extId = $extId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(?int $orden): static
    {
        $this->orden = $orden;

        return $this;
    }

    public function getLvlNumber(): ?int
    {
        return $this->lvlNumber;
    }

    public function setLvlNumber(?int $lvlNumber): static
    {
        $this->lvlNumber = $lvlNumber;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    //CUSTOM FUNCTION
    public function setCategories(array $newCategories): static
    {
        $newCategoriesCollection = new ArrayCollection();
        foreach ($newCategories as $newCategory) {
            if ($newCategory instanceof Category) {
                $newCategoriesCollection->add($newCategory);
            }
        }
        $this->categories = $newCategoriesCollection;

        return $this;
    }

    public function addCategory(self $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setparent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getparent() === $this) {
                $category->setparent(null);
            }
        }

        return $this;
    }

    public function getMediaObject(): ?MediaObject
    {
        return $this->MediaObject;
    }

    public function setMediaObject(?MediaObject $MediaObject): static
    {
        $this->MediaObject = $MediaObject;

        return $this;
    }

    public function getIdentify(): ?string
    {
        return $this->identify;
    }

    public function setIdentify(string $identify): static
    {
        $this->identify = $identify;

        return $this;
    }

    public function isIsBlockedForView(): ?bool
    {
        return $this->isBlockedForView;
    }

    public function setIsBlockedForView(bool $isBlockedForView): static
    {
        $this->isBlockedForView = $isBlockedForView;

        return $this;
    }


}