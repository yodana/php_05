<?php

namespace ex12\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bank_account
 *
 * @ORM\Table(name="bank_account_e12")
 * @ORM\Entity(repositoryClass="ex11\Repository\bank_accountRepository")
 */
class bank_account
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
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="post", inversedBy="bank_account")
     * @ORM\JoinColumn(name="bank_id", referencedColumnName="id")
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
     * Set amount.
     *
     * @param int $amount
     *
     * @return bank_account
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

     /**
     * Set amount.
     *
     * @param post 
     *
     * @return bank_account
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
