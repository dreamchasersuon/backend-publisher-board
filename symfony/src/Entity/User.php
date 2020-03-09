<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $user_name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $user_custom;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $register_date;

    /**
     * @ORM\Column(type="float")
     */
    private $balance;

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(?string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getUserCustom(): ?string
    {
        return $this->user_custom;
    }

    public function setUserCustom(?string $user_custom): self
    {
        $this->user_custom = $user_custom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setEnabled(): self
    {
        $this->enabled = true;

        return $this;
    }

    public function getRegisterDate(): ?string
    {
        return $this->register_date;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setRegisterDate(): self
    {
        $this->register_date = gmdate("Y-m-d\TH:i:s\Z");

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function prePersistBalance(): self
    {
        $this->balance = 0;

        return $this;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance + $this->balance;

        return $this;
    }
}
