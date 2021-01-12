<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GaleryRepository")
 * @Vich\Uploadable()
 */
class Galery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thumbnail;

    /**
     * @var File
     * @Vich\UploadableField(mapping="galery_thumbnails", fileNameProperty="thumbnail")
     */
    private $thumbnailFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", mappedBy="galeries", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Model", mappedBy="galeries")
     */
    private $models;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="galery", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFree;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="likedGaleries")
     */
    private $userLikes;

    /**
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="galery", orphanRemoval=true, cascade={"persist"})
     */
    private $attachments;

    /**
     * Admin field
     */
    private $attachmentFiles;

    /**
     * @ORM\Column(type="integer")
     */
    private $cupOfCoffee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $banner;

    /**
     * @var File
     * @Vich\UploadableField(mapping="galery_banner", fileNameProperty="banner")
     */
    private $bannerFile;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="galery")
     */
    private $payments;

    public function __construct()
    {
        $this->thumbnail = '../../../assets/default/thumbnail.png';
        $this->createdAt = new \DateTime('now');
        $this->categories = new ArrayCollection();
        $this->models = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->cupOfCoffee = 0;
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setThumbnailFile(File $thumbnail = null)
    {
        $this->thumbnailFile = $thumbnail;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($thumbnail) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addGalery($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeGalery($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return Collection|Model[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->addGalery($this);
        }

        return $this;
    }

    public function removeModel(Model $model): self
    {
        if ($this->models->contains($model)) {
            $this->models->removeElement($model);
            $model->removeGalery($this);
        }

        return $this;
    }

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
            $comment->setGalery($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getGalery() === $this) {
                $comment->setGalery(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttachmentFiles()
    {
        return $this->attachmentFiles;
    }

    /**
     * @return String
     */
    public function getCategoriesName(): String
    {
        setlocale(LC_CTYPE,'fr_FR');
        $result = '';

        foreach ($this->categories as $categorie){
            $result .= $categorie->getTranslitName() . ' ';
        }

        return $result;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(bool $isFree): self
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(User $userLike): self
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes[] = $userLike;
        }

        return $this;
    }

    public function removeUserLike(User $userLike): self
    {
        if ($this->userLikes->contains($userLike)) {
            $this->userLikes->removeElement($userLike);
        }

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setGalery($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->contains($attachment)) {
            $this->attachments->removeElement($attachment);
            // set the owning side to null (unless already changed)
            if ($attachment->getGalery() === $this) {
                $attachment->setGalery(null);
            }
        }

        return $this;
    }

    public function getCupOfCoffee(): ?int
    {
        return $this->cupOfCoffee;
    }

    public function setCupOfCoffee(int $cupOfCoffee): self
    {
        $this->cupOfCoffee = $cupOfCoffee;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    public function setBannerFile(File $banner = null)
    {
        $this->bannerFile = $banner;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($banner) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getBannerFile()
    {
        return $this->bannerFile;
    }

    public function get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setGalery($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getGalery() === $this) {
                $payment->setGalery(null);
            }
        }

        return $this;
    }
}
