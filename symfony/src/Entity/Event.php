<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\GreaterThanOrEqual("today", message: "La date ne peut pas être antérieure à aujourd'hui.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Artist::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artist $artist = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "events")]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string{
        return $this->name;
    }
    public function setName(string $name): void{
        $this->name = $name;
    }
    public function getDate(): ?\DateTimeInterface{
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void{
        $this->date = $date;
    }

    public function getArtist(): ?Artist{
        return $this->artist;
    }
    public function setArtist(Artist $artist): void{
        $this->artist = $artist;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addEvent($this);
        }
    }

    public function removeUser(User $user): void
    {
        if ($this->users->removeElement($user)) {
            $user->removeEvent($this);
        }
    }

    public function getCreator(): ?User
    {
        return $this->users->first() ?: null;
    }
}
