<?php

namespace ex13\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="ex13\Repository\EmployeeRepository")
 */
class Employee
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
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     */
    private $birthdate;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="employee_since", type="datetime")
     */
    private $employeeSince;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="employee_until", type="datetime")
     */
    private $employeeUntil;

    /**
     * @var int
     *
     * @ORM\Column(name="salary", type="integer")
     */
    private $salary;
    
    /**
     * @var int
     *
    * @ORM\Column(name="hours", type="string", columnDefinition="enum('8', '6', '4')")
    */
    private $hours;

     /**
     * @var int
     *
    * @ORM\Column(name="position", type="string", columnDefinition="enum('manager', 'account_manager', 'qa_manager', 'dev_manager', 'ceo', 'coo', 'backend_dev', 'frontend_dev', 'qa_tester')")
    */
    private $position;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="employee", inversedBy="employee")
     * @ORM\JoinColumn(name="superiors", referencedColumnName="id")
     */
    private $superiors;

    /**
     * @var int
     *
     * @ORM\OneToMany(targetEntity="employee", mappedBy="superiors")
     */
    private $employee;

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
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return Employee
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return Employee
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Employee
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set birthdate.
     *
     * @param \DateTime $birthdate
     *
     * @return Employee
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate.
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Employee
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set employeeSince.
     *
     * @param \DateTime $employeeSince
     *
     * @return Employee
     */
    public function setEmployeeSince($employeeSince)
    {
        $this->employeeSince = $employeeSince;

        return $this;
    }

    /**
     * Get employeeSince.
     *
     * @return \DateTime
     */
    public function getEmployeeSince()
    {
        return $this->employeeSince;
    }

    /**
     * Set employeeUntil.
     *
     * @param \DateTime $employeeUntil
     *
     * @return Employee
     */
    public function setEmployeeUntil($employeeUntil)
    {
        $this->employeeUntil = $employeeUntil;

        return $this;
    }

    /**
     * Get employeeUntil.
     *
     * @return \DateTime
     */
    public function getEmployeeUntil()
    {
        return $this->employeeUntil;
    }

    /**
     * Set salary.
     *
     * @param int $salary
     *
     * @return Employee
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary.
     *
     * @return int
     */
    public function getSalary()
    {
        return $this->salary;
    }
}
