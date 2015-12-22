<?php
/**
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com) (C) 2015
 * @license     GPLv3
 *
 * @date        22.12.2015
 */
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

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
     * @var int
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     * @var string
     */
    private $code;

    /** 
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    public $date_added;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var \DateTime
	 */
	public $date_removed;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 * @var \DateTime
	 */
	public $date_updated;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationLocalization",
     *     mappedBy="navigation"
     * )
     * @var array
     */
    protected $localizations;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem",
     *     mappedBy="navigation"
     * )
     * @var array
     */
    private $items;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="CASCADE")
     * @var \BiberLtd\Bundle\SiteManagementBundle\Entity\Site
     */
    private $site;

	/**
	 * @return mixed
	 */
    public function getId(){
        return $this->id;
    }

	/**
	 * @param string $code
	 *
	 * @return $this
	 */
    public function setCode(\string $code) {
        if($this->setModified('code', $code)->isModified()) {
            $this->code = $code;
        }
        return $this;
    }

	/**
	 * @return string
	 */
    public function getCode() {
        return $this->code;
    }

	/**
	 * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem $navigation_items
	 *
	 * @return $this
	 */
    public function setNavigationItems(\BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem $navigation_items) {
        if($this->setModified('navigation_items', $navigation_items)->isModified()) {
            $this->navigation_items = $navigation_items;
        }
        return $this;
    }

	/**
	 * @return mixed
	 */
    public function getNavigationItems() {
        return $this->navigation_items;
    }

	/**
	 * @param \BiberLtd\Bundle\SiteManagementBundle\Entity\Site $site
	 *
	 * @return $this
	 */
    public function setSite(\BiberLtd\Bundle\SiteManagementBundle\Entity\Site $site) {
        if($this->setModified('site', $site)->isModified()) {
            $this->site = $site;
        }
        return $this;
    }

	/**
	 * @return \BiberLtd\Bundle\SiteManagementBundle\Entity\Site
	 */
    public function getSite() {
        return $this->site;
    }

	/**
	 * @return array
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @param array $items
	 *
	 * @return $this
	 */
	public function setItems(array $items) {
		if (!$this->setModified('items', $items)->isModified()) {
			return $this;
		}
		$this->items = $items;

		return $this;
	}
}