<?php

namespace App\Entity;

use ApiPlatform\Elasticsearch\Filter\TermFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Enum\CatalogDocumentTypeEnum;
use App\Repository\ProductRepository;
use App\State\ProductProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Patch(),
    ],
    normalizationContext: [
        'groups' => ['product:read'],
    ],
    denormalizationContext: [
        'groups' => ['product:write'],
    ],
    paginationClientItemsPerPage: true,
)]
#[ApiFilter(OrderFilter::class, properties: ['sku', 'title'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'sku' => 'exact',
        'title' => 'partial',
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/catalog/{documentType}/{lvl1}/{lvl2}/{lvl3}',
            uriVariables: [
                'documentType' => new Link(fromClass: CatalogDocumentTypeEnum::class),
                'lvl1' => new Link(fromClass: Category::class),
                'lvl2' => new Link(fromClass: Category::class),
                'lvl3' => new Link(fromClass: Category::class),
            ],
            paginationClientItemsPerPage: true,
            normalizationContext: [
                'groups' => ['product:read'],
            ],
            denormalizationContext: [
                'groups' => ['product:write'],
            ],
            provider: ProductProvider::class,
        )
    ],
)]

class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read','category:read','restoreCart:read'])]
    private ?int $id = null;

    #[Groups(['product:read','category:read','historyDetailed:read','history:read','restoreCart:read'])]
    #[ORM\Column(length: 255)]
    private ?string $sku = null;

    #[Groups(['product:read','category:read','product:write','historyDetailed:read','history:read','restoreCart:read'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(['product:read','category:read','product:write','restoreCart:read','history:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $defaultImagePath = null;

    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $barcode = null;

    #[Groups(['product:read','category:read','product:write','restoreCart:read','history:read'])]
    #[ORM\Column]
    private ?bool $isPublished = null;



    #[Groups(['product:read','category:read','productImages:read','restoreCart:read','history:read'])]
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImages::class)]
    private Collection $imagePath;

    #[Ignore]
    #[ORM\OneToMany(mappedBy: 'sku', targetEntity: Migvan::class)]
    private Collection $migvans;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: PriceListDetailed::class)]
    private Collection $priceListDetaileds;


    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column(nullable: true)]
    private ?int $basePrice = null;

    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column(nullable: true)]
    private ?int $finalPrice = 0;

    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column]
    private ?int $stock = 0;

    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column(nullable: true)]
    private ?int $packQuantity = null;

    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    #[ORM\Column(nullable: true)]
    private ?int $discount = 0;

    #[Groups(['product:read','category:read','product:write','history:read'])]
    #[ORM\Column]
    private ?int $orden = null;

    #[Groups(['product:read','category:read','history:read'])]
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductAttribute::class)]
    private Collection $productAttributes;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: PackProducts::class)]
    #[Groups(['product:read','category:read','restoreCart:read','history:read'])]
    private Collection $packProducts;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extLvl2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extLvl3 = null;

    #[ORM\Column]
    #[Groups(['product:read','category:read','product:write','restoreCart:read','history:read'])]
    private ?bool $isHumane = null;

    #[ORM\Column]
    #[Groups(['product:read','category:read','product:write','restoreCart:read','history:read'])]
    private ?bool $isVeterinary = null;

    #[ORM\Column]
    #[Groups(['product:read','category:read','product:write','restoreCart:read','history:read'])]
    private ?bool $isPharmecies = null;

    #[ORM\Column]
    #[Groups(['product:read','category:read','product:write','restoreCart:read','history:read'])]
    private ?bool $isMedicalCenter = null;

    #[ORM\Column]
    #[Groups(['product:read','category:read','product:write','restoreCart:read','history:read'])]
    private ?bool $isHospital = null;

    #[ORM\Column]
    #[Groups(['product:read','category:read','product:write','restoreCart:read','history:read'])]
    private ?bool $isDrugNotInBasket = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['product:read','category:read','historyDetailed:read','history:read','restoreCart:read'])]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['product:read','category:read','historyDetailed:read','history:read','restoreCart:read'])]
    private ?string $linkTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $innerHtml = null;


    public function __construct()
    {
        $this->imagePath = new ArrayCollection();
        $this->migvans = new ArrayCollection();
        $this->priceListDetaileds = new ArrayCollection();
        $this->productAttributes = new ArrayCollection();
        $this->packProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

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

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): static
    {
        $this->barcode = $barcode;

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





    /**
     * @return Collection<int, ProductImages>
     */
    public function getImagePath(): Collection
    {
        return $this->imagePath;
    }

    public function addImagePath(ProductImages $imagePath): static
    {
        if (!$this->imagePath->contains($imagePath)) {
            $this->imagePath->add($imagePath);
            $imagePath->setProductId($this);
        }

        return $this;
    }

    public function removeImagePath(ProductImages $imagePath): static
    {
        if ($this->imagePath->removeElement($imagePath)) {
            // set the owning side to null (unless already changed)
            if ($imagePath->getProductId() === $this) {
                $imagePath->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Migvan>
     */
    public function getMigvans(): Collection
    {
        return $this->migvans;
    }

    public function addMigvan(Migvan $migvan): static
    {
        if (!$this->migvans->contains($migvan)) {
            $this->migvans->add($migvan);
            $migvan->setSku($this);
        }

        return $this;
    }

    public function removeMigvan(Migvan $migvan): static
    {
        if ($this->migvans->removeElement($migvan)) {
            // set the owning side to null (unless already changed)
            if ($migvan->getSku() === $this) {
                $migvan->setSku(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PriceListDetailed>
     */
    public function getPriceListDetaileds(): Collection
    {
        return $this->priceListDetaileds;
    }

    public function addPriceListDetailed(PriceListDetailed $priceListDetailed): static
    {
        if (!$this->priceListDetaileds->contains($priceListDetailed)) {
            $this->priceListDetaileds->add($priceListDetailed);
            $priceListDetailed->setProductId($this);
        }

        return $this;
    }

    public function removePriceListDetailed(PriceListDetailed $priceListDetailed): static
    {
        if ($this->priceListDetaileds->removeElement($priceListDetailed)) {
            // set the owning side to null (unless already changed)
            if ($priceListDetailed->getProductId() === $this) {
                $priceListDetailed->setProductId(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getBasePrice(): ?int
    {
        return $this->basePrice;
    }

    public function setBasePrice(?int $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getFinalPrice(): ?int
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(?int $finalPrice): static
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }

    public function getPackQuantity(): ?int
    {
        return $this->packQuantity;
    }

    public function setPackQuantity(?int $packQuantity): static
    {
        $this->packQuantity = $packQuantity;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDefaultImagePath(): ?string
    {
        return $this->defaultImagePath;
    }

    public function setDefaultImagePath(?string $defaultImagePath): static
    {
        $this->defaultImagePath = $defaultImagePath;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): static
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * @return Collection<int, ProductAttribute>
     */
    public function getProductAttributes(): Collection
    {
        return $this->productAttributes;
    }

    public function addProductAttribute(ProductAttribute $productAttribute): static
    {
        if (!$this->productAttributes->contains($productAttribute)) {
            $this->productAttributes->add($productAttribute);
            $productAttribute->setProduct($this);
        }

        return $this;
    }

    public function removeProductAttribute(ProductAttribute $productAttribute): static
    {
        if ($this->productAttributes->removeElement($productAttribute)) {
            // set the owning side to null (unless already changed)
            if ($productAttribute->getProduct() === $this) {
                $productAttribute->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PackProducts>
     */
    public function getPackProducts(): Collection
    {
        return $this->packProducts;
    }

    public function addPackProduct(PackProducts $packProduct): static
    {
        if (!$this->packProducts->contains($packProduct)) {
            $this->packProducts->add($packProduct);
            $packProduct->setProduct($this);
        }

        return $this;
    }

    public function removePackProduct(PackProducts $packProduct): static
    {
        if ($this->packProducts->removeElement($packProduct)) {
            // set the owning side to null (unless already changed)
            if ($packProduct->getProduct() === $this) {
                $packProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getExtLvl2(): ?string
    {
        return $this->extLvl2;
    }

    public function setExtLvl2(?string $extLvl2): static
    {
        $this->extLvl2 = $extLvl2;

        return $this;
    }

    public function getExtLvl3(): ?string
    {
        return $this->extLvl3;
    }

    public function setExtLvl3(?string $extLvl3): static
    {
        $this->extLvl3 = $extLvl3;

        return $this;
    }

    public function isIsHumane(): ?bool
    {
        return $this->isHumane;
    }

    public function setIsHumane(bool $isHumane): static
    {
        $this->isHumane = $isHumane;

        return $this;
    }

    public function isIsVeterinary(): ?bool
    {
        return $this->isVeterinary;
    }

    public function setIsVeterinary(bool $isVeterinary): static
    {
        $this->isVeterinary = $isVeterinary;

        return $this;
    }

    public function isIsPharmecies(): ?bool
    {
        return $this->isPharmecies;
    }

    public function setIsPharmecies(bool $isPharmecies): static
    {
        $this->isPharmecies = $isPharmecies;

        return $this;
    }

    public function isIsMedicalCenter(): ?bool
    {
        return $this->isMedicalCenter;
    }

    public function setIsMedicalCenter(bool $isMedicalCenter): static
    {
        $this->isMedicalCenter = $isMedicalCenter;

        return $this;
    }

    public function isIsHospital(): ?bool
    {
        return $this->isHospital;
    }

    public function setIsHospital(bool $isHospital): static
    {
        $this->isHospital = $isHospital;

        return $this;
    }

    public function isIsDrugNotInBasket(): ?bool
    {
        return $this->isDrugNotInBasket;
    }

    public function setIsDrugNotInBasket(bool $isDrugNotInBasket): static
    {
        $this->isDrugNotInBasket = $isDrugNotInBasket;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getLinkTitle(): ?string
    {
        return $this->linkTitle;
    }

    public function setLinkTitle(?string $linkTitle): static
    {
        $this->linkTitle = $linkTitle;

        return $this;
    }

    public function getInnerHtml(): ?string
    {
        return $this->innerHtml;
    }

    public function setInnerHtml(?string $innerHtml): static
    {
        $this->innerHtml = $innerHtml;

        return $this;
    }

}
