<?php
namespace Lyssal\MonnaieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Monnaie.
 * 
 * @author Rémi Leclerc <rleclerc@Lyssal.com>
 * @ORM\MappedSuperclass
 */
abstract class Monnaie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="monnaie_id", type="smallint", nullable=false, options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="monnaie_code", type="string", nullable=false, length=3)
     * @Assert\NotBlank
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="monnaie_symbole", type="string", nullable=false, length=3)
     * @Assert\NotBlank
     */
    protected $symbole;
    
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set code
     *
     * @param string $code
     * @return Monnaie
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }
    
    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * Set symbole
     *
     * @param string $symbole
     * @return Monnaie
     */
    public function setSymbole($symbole)
    {
        $this->symbole = $symbole;
    
        return $this;
    }
    
    /**
     * Get symbole
     *
     * @return string
     */
    public function getSymbole()
    {
        return $this->symbole;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }
}
