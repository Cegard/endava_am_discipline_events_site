<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;
    
    /**
     * @ORM\Column(type="string", length=15)
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $password;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="owner")
     */
    private $events;
    
    
    public function __construct(){
        $this->events = new ArrayCollection();
    }
    
    
    public function getId(){
        return $this->id;
    }
    
    
    public function getName(): ?string {
        return $this->name;
    }
    
    
    public function setName(string $name): self {
        $this->name = $name;
        
        return $this;
    }
    
    
    public function getLastName(): ?string {
        return $this->lastName;
    }
    
    
    public function setLastName(string $lastName): self {
        $this->lastName = $lastName;
        
        return $this;
    }
    
    
    public function getPhone(): ?string {
        return $this->phone;
    }
    
    
    public function setPhone(string $phone): self {
        $this->phone = $phone;
        
        return $this;
    }
    
    
    public function getEmail(): ?string {
        return $this->email;
    }
    
    
    public function setEmail(string $email): self {
        $this->email = $email;
        
        return $this;
    }
    
    
    public function getPassword(): ?string {
        return $this->password;
    }
    
    
    public function setPassword(string $password): self {
        $this->password = $password;
        
        return $this;
    }
    
    
    /**
     *@return Collection|Event[]
     */
    public function getEvents(){
        return $this->events;
    }
}
