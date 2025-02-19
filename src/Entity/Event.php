<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?Date $date = null;

    #[ORM\Column]
    private ?Artist $artist = null;


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
    public function getDate(): ?Date{
        return $this->date;
    }
    public function setDate(Date $date): void{
        $this->date = $date;
    }
    public function getArtist(): ?Artist{
        return $this->artist;
    }
    public function setArtist(Artist $artist): void{
        $this->artist = $artist;
    }
}
