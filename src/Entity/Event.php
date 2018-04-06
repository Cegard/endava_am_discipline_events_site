<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $publishdDate;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=80, nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVirtual;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $address;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="eventsCreated")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;
    
    /**
     *@ORM\ManyToMany(targetEntity="App\Entity\Person", mappedBy="eventsInvitations")
     */
    private $invitedPeople;
    
    
    public function __construct(){
        $this->isVirtual = false;
        $this->invitedPeople = new ArrayCollection();
    }
    

    public function getId()
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

    public function getPublishdDate(): ?\DateTimeInterface
    {
        return $this->publishdDate;
    }

    public function setPublishdDate(\DateTimeInterface $publishdDate): self
    {
        $this->publishdDate = $publishdDate;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
