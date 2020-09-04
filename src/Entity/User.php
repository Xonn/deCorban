<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message="L'email est déjà utilisé.")
 * @UniqueEntity("username", message="Le pseudo est déjà pris.")
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire au minimum 8 caractères.")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passes ne correspondent pas.")
     */
    private $confirm_password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

     /**
      * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @var File
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="image")
     */
    private $imageFile;
 
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity=Galery::class, mappedBy="userLikes")
     */
    private $likedGaleries;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->image = '../../../assets/Blank-avatar.png';
        $this->isActive = TRUE;
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->comments = new ArrayCollection();
        $this->likedGaleries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirm_password;
    }

    public function setConfirmPassword(string $confirm_password): self
    {
        $this->confirm_password = $confirm_password;

        return $this;
    }

    /**
     * Return user roles.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
 
        return array_unique($roles);
    }
 
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
 
    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
 
    public function getUpdatedAt(){
        return $this->updatedAt;
    }

    public function getSalt() {}
    public function eraseCredentials () {}

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getUsername();
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
     * @return Collection|Galery[]
     */
    public function getLikedGaleries(): Collection
    {
        return $this->likedGaleries;
    }

    public function addLikedGalery(Galery $likedGalery): self
    {
        if (!$this->likedGaleries->contains($likedGalery)) {
            $this->likedGaleries[] = $likedGalery;
            $likedGalery->addUserLike($this);
        }

        return $this;
    }

    public function removeLikedGalery(Galery $likedGalery): self
    {
        if ($this->likedGaleries->contains($likedGalery)) {
            $this->likedGaleries->removeElement($likedGalery);
            $likedGalery->removeUserLike($this);
        }

        return $this;
    }

    public function isLiking(Galery $galery) {
        return in_array($galery, $this->getLikedGaleries()->getValues());
    }
}
