<?php
namespace BiberLtd\Core\Bundles\ContentManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
/**
 * @name        navigation
 * @package		BiberLtd\Core\AccessManagementBundle
 *
 * @author		Can Berkol
 * @version     1.2.0
 * @date        19.12.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
use BiberLtd\Core\CoreLocalizableEntity;
/** 
 * @ORM\Entity
 * @ORM\Table(
 *     name="navigation",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={@ORM\Index(name="idx_n_date_added", columns={"date_added"})},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_navigation_id", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idx_u_navigation_code", columns={"code"})
 *     }
 * )
 */
class Navigation extends CoreLocalizableEntity
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     */
    private $code;

    /** 
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $date_added;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\NavigationLocalization",
     *     mappedBy="navigation"
     * )
     */
    protected $localizations;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\NavigationItem",
     *     mappedBy="navigation"
     * )
     */
    private $navigation_items;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Core\Bundles\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="CASCADE")
     */
    private $site;

    /**
     * @name            getId()
     *  				Gets $id property.
     * .
     * @author          Can Berkol
     * @since			1.0.0
     * @version         1.2.0
     *
     * @return          string          $this->id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @name            setCode ()
     *                  Sets the code property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $code
     *
     * @return          object                $this
     */
    public function setCode($code) {
        if($this->setModified('code', $code)->isModified()) {
            $this->code = $code;
        }
        return $this;
    }

    /**
     * @name            getCode ()
     *                  Returns the value of code property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @return          mixed           $this->code
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @name            setNavigationItems()
     *                  Sets the navigation_items property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $navigation_items
     *
     * @return          object                $this
     */
    public function setNavigationItems($navigation_items) {
        if($this->setModified('navigation_items', $navigation_items)->isModified()) {
            $this->navigation_items = $navigation_items;
        }
        return $this;
    }

    /**
     * @name            getNavigationItems()
     *                  Returns the value of navigation_items property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @return          mixed           $this->navigation_items
     */
    public function getNavigationItems() {
        return $this->navigation_items;
    }

    /**
     * @name            setSite ()
     *                  Sets the site property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $site
     *
     * @return          object                $this
     */
    public function setSite($site) {
        if($this->setModified('site', $site)->isModified()) {
            $this->site = $site;
        }
        return $this;
    }

    /**
     * @name            getSite ()
     *                  Returns the value of site property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @return          mixed           $this->site
     */
    public function getSite() {
        return $this->site;
    }
}
/**
 * Change Log:
 * **************************************
 * v1.2.0                      Can Berkol
 * 19.12.2013
 * **************************************
 * File resetted.
 *
 */