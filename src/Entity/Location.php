<?php

namespace App\Entity;

use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="point", nullable=true)
     */
    private $geometry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGeometry()
    {
        return $this->geometry;
    }

    public function setGeometry(?Point $geometry): self
    {
        $this->geometry = $geometry;

        return $this;
    }
}
