<?php

namespace ex09\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * addresses
 *
 * @ORM\Table(name="addresses")
 * @ORM\Entity(repositoryClass="ex09\Repository\addressesRepository")
 */
class addresses
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

      /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="post", inversedBy="addresses")
     * @ORM\JoinColumn(name="addresses_id", referencedColumnName="id")
     */
    private $post;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return addresses
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

      /**
     * Set amount.
     *
     * @param post 
     *
     * @return addresses
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
