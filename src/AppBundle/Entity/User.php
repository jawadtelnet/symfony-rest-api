<?php
namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
* @ORM\Entity()
* @UniqueEntity("email")
* @ORM\Table(name="users"),
*      uniqueConstraints={@ORM\UniqueConstraint(name="users_email_unique",columns={"email"})}
*/
class User implements UserInterface
{
   /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
    * @Assert\NotBlank  
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\OneToMany(targetEntity="Preference", mappedBy="user")
     * @var Preference[]
     */
    protected $preferences;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    protected $plainPassword;

    const MATCH_VALUE_THRESHOLD = 25;

    public function preferencesMatch($themes)
    {
        $matchValue = 0;
        foreach ($this->preferences as $preference) {
            foreach ($themes as $theme) {
                if ($preference->match($theme)) {
                    $matchValue += $preference->getValue() * $theme->getValue();
                }
            }
        }

        return $matchValue >= self::MATCH_VALUE_THRESHOLD;
    }

    public function __construct()
    {
        $this->preferences = new ArrayCollection();
    }

    
        public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // Suppression des données sensibles
        $this->plainPassword = null;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
    

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }



    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPreferences()
    {
        return $this->preferences;
    }

    public function setPreferences($preferences)
    {
        $this->preferences = $preferences;
    }
}