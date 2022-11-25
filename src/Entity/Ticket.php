<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $passport_id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $destination;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $departure_time;

    /**
     * @ORM\Column(type="integer")
     */
    private $seat_number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassportId(): ?string
    {
        return $this->passport_id;
    }

    public function setPassportId(?string $passport_id): self
    {
        $this->passport_id = $passport_id;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDepartureTime(): ?string
    {
        return $this->departure_time;
    }

    public function setDepartureTime(?string $departure_time): self
    {
        $this->departure_time = $departure_time;

        return $this;
    }

    public function getSeatNumber(): ?int
    {
        return $this->seat_number;
    }

    public function setSeatNumber(int $seat_number): self
    {
        $this->seat_number = $seat_number;

        return $this;
    }
}
