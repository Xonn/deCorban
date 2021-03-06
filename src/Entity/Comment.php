<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Galery", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $galery;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Comment", inversedBy="comments")
     */
    private $replyTo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Comment", mappedBy="replyTo")
     */
    private $comments;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->isPublished = TRUE;
        $this->replyTo = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGalery(): ?Galery
    {
        return $this->galery;
    }

    public function setGalery(?Galery $galery): self
    {
        $this->galery = $galery;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getMessage();
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

    /**
     * @return Collection|self[]
     */
    public function getReplyTo(): Collection
    {
        return $this->replyTo;
    }

    public function addReplyTo(self $replyTo): self
    {
        if (!$this->replyTo->contains($replyTo)) {
            $this->replyTo[] = $replyTo;
        }

        return $this;
    }

    public function removeReplyTo(self $replyTo): self
    {
        if ($this->replyTo->contains($replyTo)) {
            $this->replyTo->removeElement($replyTo);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->addReplyTo($this);
        }

        return $this;
    }

    public function removeComment(self $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            $comment->removeReplyTo($this);
        }

        return $this;
    }

    public function isReply(): bool 
    {
        return !$this->replyTo->isEmpty();
    }

    public function get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
