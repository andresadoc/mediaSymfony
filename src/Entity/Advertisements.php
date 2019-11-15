<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UploadMediaAction;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\{PrePersist, PreUpdate};
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertisementsRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
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
 *              "method"="POST",
 *              "path"="/advertisements",
 *              "controller"=UploadMediaAction::class,
 *              "defaults"={"_api_receive"=false},
 *              "denormalization_context"={
 *                  "groups" = {"post"}
 *              },
 *              "normalization_context"={
 *                  "groups"={"get-user"}
 *              }
 *          }
 *      }
 * )
 * TODO: Check the issue with {"security"="is_granted('ROLE_ADVERTISER')"}
 */
class Advertisements implements AdvertisementsInterface
{
    /**
     * Constants for advertisement state
     */
    const STOPPED = "Stopped";
    const PUBLISHING = "Publishing";
    const PUBLISHED = "Published";

    /**
     * Constants for advertisement media type
     */
    const IMAGE = "IMAGE";
    const VIDEO = "VIDEO";
    const TEXT = "TEXT";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-user", "post", "put"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"get-user", "post", "put"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer", name="x_position")
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Groups({"get-user", "post", "put"})
     */
    private $xPosition;

    /**
     * @ORM\Column(type="integer", name="y_position")
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Groups({"get-user", "post", "put"})
     */
    private $yPosition;

    /**
     * @ORM\Column(type="integer", name="z_position")
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Groups({"get-user", "post", "put"})
     */
    private $zPosition;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Groups({"get-user", "post", "put"})
     */
    private $width;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Groups({"get-user", "post", "put"})
     */
    private $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Positive()
     * @Groups({"get-user", "post", "put"})
     * TODO: Assign media weight automatically
     */
    private $weight;

    /**
     * @ORM\Column(type="string", name="external_image", length=255, nullable=true)
     * @Groups({"get-user", "post", "put"})
     */
    private $externalImage;

    /**
     * @ORM\Column(type="string", name="media_type", length=10)
     * @Groups({"get-user", "post", "put"})
     */
    private $mediaType;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-user", "post", "put"})
     */
    private $media;

    /**
     * @Vich\UploadableField(mapping="media", fileNameProperty="media")
     * @Groups({"post", "put"})
     */
    private $file;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-user", "post", "put"})
     */
    private $text;

    /**
     * @ORM\Column(type="boolean", name="is_active")
     * @Assert\NotBlank()
     * @Groups({"get-user", "post", "put"})
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="advertisements")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-user", "post", "put"})
     */
    private $creator;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"get-user", "post", "put"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="modified_at")
     * @Groups({"get-user", "post", "put"})
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"get-user", "post", "put"})
     */
    private $state;

    public function __construct()
    {
        $this -> campaigns = new ArrayCollection();
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

    public function getXPosition(): ?int
    {
        return $this->xPosition;
    }

    public function setXPosition(int $xPosition): self
    {
        $this->xPosition = $xPosition;

        return $this;
    }

    public function getYPosition(): ?int
    {
        return $this->yPosition;
    }

    public function setYPosition(int $yPosition): self
    {
        $this->yPosition = $yPosition;

        return $this;
    }

    public function getZPosition(): ?int
    {
        return $this->zPosition;
    }

    public function setZPosition(int $zPosition): self
    {
        $this->zPosition = $zPosition;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getExternalImage(): ?string
    {
        return $this->externalImage;
    }

    public function setExternalImage(?string $externalImage): self
    {
        $this->externalImage = $externalImage;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getMedia()
    {
        return is_null($this->media) ? null : "/media/".$this->media;
    }

    public function setMedia($media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @PrePersist
     */
    public function onPrePersist():void{
        $this->state = self::STOPPED;
        $this->createdAt = new DateTime("NOW");
        $this->modifiedAt = new DateTime("NOW");
    }

    /**
     * @PreUpdate
     */
    public function onPreUpdate():void{
        $this->modifiedAt = new DateTime("NOW");
    }

    /**
     * Method to check if an advertisement is valid by media type
     */
    public function validateByMediaType():?bool
    {
        $exception = "";

        switch ($this -> getMediaType()) {
            case self::IMAGE:
                //Image mediaType exception
                if(is_null($this -> getMedia()) or
                    !preg_match("/^.*\.(jpg|jpeg|png)$/i", $this -> getMedia())){
                    $exception .= "Media not valid (jpg|jpeg|png). Value (".$this -> getMedia()."). \n";
                }

                //External image exception
                if(is_null($this -> getExternalImage()) or
                    empty(trim($this -> getExternalImage()))){
                    $exception .= "External image could not be null or empty. Value (".$this -> getExternalImage()."). \n";
                }

                //Weight exception
                if(is_null($this -> getWeight()) or
                    $this -> getWeight() <= 0){
                    $exception .= "Weight couldn't be null or less than 0. Value (".$this -> getWeight()."). \n";
                }
            break;

            case self::VIDEO:
                //Video mediaType exception
                if(is_null($this -> getMedia()) or
                    !preg_match("/^.*\.(mp4|webm)$/i", $this -> getMedia())){
                    $exception .= "Media not valid (mp4|webm). Value (".$this -> getMedia()."). \n";
                }

                //External image exception
                if(is_null($this -> getExternalImage()) or
                    empty(trim($this -> getExternalImage()))){
                    $exception .= "External image could not be null or empty. Value (".$this -> getExternalImage()."). \n";
                }

                //Weight exception
                if(is_null($this -> getWeight()) or
                    $this -> getWeight() <= 0){
                    $exception .= "Weight couldn't be null or less than 0. Value (".$this -> getWeight()."). \n";
                }
            break;

            case self::TEXT:
                //Text
                if(is_null($this -> getText()) or
                    strlen($this -> getText()) > 140 or
                    strlen($this -> getText()) < 1){
                    $exception .= "Text content could not be null or greater than 140 characters. Value (".$this -> getText()."). \n";
                }
            break;

            default:
                $exception .= "Media Type could not be found. Value (".$this -> getMediaType()."). \n";
                break;
        }

        /**
         * TODO: Throw a Bad Parameter exception
         */
        if(!empty($exception)){
            throw new InvalidArgumentException($exception);
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }
}