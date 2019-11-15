<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\{PrePersist, PreUpdate};
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"get-user"}
 *              }
 *          },
 *          "put"={
 *              "security"="object.getCreator() == user",
 *              "denormalization_context"={
 *                  "groups" = {"put"}
 *              },
 *              "normalization_context"={
 *                  "groups"={"get-user"}
 *              }
 *          },
 *          "delete"={
 *              "security"="object.getCreator() == user"
 *          }
 *     },
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"get-user"}
 *              }
 *          },
 *          "post"={
 *              "denormalization_context"={
 *                  "groups" = {"post"}
 *              },
 *              "normalization_context"={
 *                  "groups"={"get-user"}
 *              }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CampaignRepository")
 * @ORM\Table(name="campaigns")
 * @ORM\HasLifecycleCallbacks()
 */
class Campaign
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"get-user", "post", "put"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", name="initial_date")
     * @Assert\NotBlank()
     * @Groups({"get-user", "post", "put"})
     */
    private $initialDate;

    /**
     * @ORM\Column(type="datetime", name="end_date")
     * @Assert\NotBlank()
     * @Groups({"get-user", "post", "put"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="boolean", name="is_active")
     * @Assert\NotBlank()
     * @Groups({"get-user", "post", "put"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"get-user"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="modified_at")
     * @Groups({"get-user"})
     */
    private $modifiedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="campaigns")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @Groups({"get-user", "post", "put"})
     */
    private $publisher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="campaignsCreator")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-user", "post", "put"})
     */
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Advertisements")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-user", "post", "put"})
     */
    private $advertisements;

    public function __construct()
    {
        $this->advertisements=new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInitialDate(): ?DateTimeInterface
    {
        return $this->initialDate;
    }

    public function setInitialDate(DateTimeInterface $initialDate): self
    {
        $this->initialDate = $initialDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getPublisher(): ?User
    {
        return $this->publisher;
    }

    /**
     * @param User $publisher
     * @return $this
     */
    public function setPublisher(User $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     * @return $this
     */
    public function setCreator(User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdvertisements()
    {
        return $this->advertisements;
    }

    /**
     * @param Advertisements $advertisement
     */
    public function addAdvertisement(Advertisements $advertisement)
    {
        $this->advertisements->add($advertisement);
    }

    /**
     * @param Advertisements $advertisement
     */
    public function removeAdvertisement(Advertisements $advertisement)
    {
        $this->advertisements->removeElement($advertisement);
    }

    /**
     * @PrePersist
     */
    public function onPrePersist():void{
        $this->createdAt = new DateTime("NOW");
        $this->modifiedAt = new DateTime("NOW");
    }

    /**
     * @PreUpdate
     */
    public function onPreUpdate():void{
        $this->modifiedAt = new DateTime("NOW");
    }
}
