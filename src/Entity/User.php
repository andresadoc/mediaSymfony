<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\{PrePersist, PreUpdate};
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"get", "get-user"}
 *              }
 *          },
 *          "put"={
 *              "security"="object == user",
 *              "denormalization_context"={
 *                  "groups" = {"put"}
 *              },
 *              "normalization_context"={
 *                  "groups"={"get"}
 *              }
 *          }
 *     },
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"get", "get-user"}
 *              }
 *          },
 *          "post"={
 *              "denormalization_context"={
 *                  "groups" = {"post"}
 *              },
 *              "normalization_context"={
 *                  "groups"={"get", "get-user"}
 *              }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("username")
 */
class User implements UserInterface
{
    /**
     * Constants for user roles
     */
    const ROLE_ADVERTISER = "ADVERTISER";
    const ROLE_PUBLISHER = "PUBLISHER";
    const ROLE_ADMIN = "ADMIN";
    const ROLE_DEFAULT = self::ROLE_PUBLISHER;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     * @Groups({"get", "get-user", "post", "put"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=20)
     * @Groups({"get", "get-user", "post"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Password must not be null.")
     * @Groups({"post", "put"})
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", name="is_active")
     * @Assert\NotBlank()
     * @Groups({"get", "post", "put"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"get"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="modified_at")
     * @Groups({"get"})
     */
    private $modifiedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advertisements", mappedBy="user")
     */
    private $advertisements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Campaign", mappedBy="publisher")
     */
    private $campaigns;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Campaign", mappedBy="creator")
     */
    private $campaignsCreator;

    /**
     * @ORM\Column(type="simple_array", length=50)
     * @Groups({"post","put"})
     */
    private $roles;

    public function __construct()
    {
        $this -> advertisements = new ArrayCollection();
        $this -> campaigns = new ArrayCollection();
        $this -> campaignsCreator = new ArrayCollection();
        $this -> roles = [self::ROLE_DEFAULT];
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
     * @return ArrayCollection
     */
    public function getAdvertisements()
    {
        return $this->advertisements;
    }

    /**
     * Returns the roles granted to the user.
     * @return array (Role|string)[] The user roles
     */
    public function getRoles():array
    {
        return $this -> roles;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles):self
    {
        $this -> roles = $roles;
        return $this;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword():?string
    {
        return $this -> password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt():?string
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername():?string
    {
        return $this -> username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

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