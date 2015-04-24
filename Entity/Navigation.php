<?php
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
/**
 * @name        Navigation
 * @package		BiberLtd\Core\ContentManagementBundle
 *
 * @author		Can Berkol
 * @version     1.2.1
 * @date        24.05.2015
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 */
use BiberLtd\Bundle\CoreBundle\CoreLocalizableEntity;
/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="navigation",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={
 *         @ORM\Index(name="idxNNaviagationDateAdded", columns={"date_added"}),
 *         @ORM\Index(name="idxNNavigationDateRemoved", columns={"date_removed"}),
 *         @ORM\Index(name="idxNNavigationDateUpdated", columns={"date_updated"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUNaviagationId", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idxUNaviagationCode", columns={"code"})
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
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	public $date_removed;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	public $date_updated;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationLocalization",
     *     mappedBy="navigation"
     * )
     */
    protected $localizations;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem",
     *     mappedBy="navigation"
     * )
     */
    private $items;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
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

	/**
	 * @name    	getItems ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @name        setItems ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param mixed $items
	 *
	 * @return $this
	 */
	public function setItems($items) {
		if (!$this->setModified('items', $items)->isModified()) {
			return $this;
		}
		$this->items = $items;

		return $this;
	}
}
/**
 * Change Log:
 * **************************************
 * v1.2.1                      24.04.2015
 * TW #3568843,
 * Can Berkol
 * **************************************
 * A getItems()
 * A setItems()
 *
 * **************************************
 * v1.2.0                      Can Berkol
 * 19.12.2013
 * **************************************
 * File resetted.
 *
 */