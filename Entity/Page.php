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
 *     name="page",
 *     options={"charset":"utf8","collate":"utf8_Turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUPageId", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idxUPageCode", columns={"code"})
 *     }
 * )
 */
class Page extends CoreLocalizableEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=15)
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
     * @ORM\Column(type="string", length=1, nullable=false, options={"default":"e"})
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     * @var string
     */
    private $bundle_name;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout", mappedBy="page")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout
     */
    private $modules_of_layout;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem", mappedBy="page")
     * @var array
     */
    private $navigation_items;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\PageLocalization", mappedBy="page")
     * @var array
     */
    protected $localizations;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", nullable=false)
     * @var \BiberLtd\Bundle\SiteManagementBundle\Entity\Site
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Layout", inversedBy="pages")
     * @ORM\JoinColumn(name="layout", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Layout
     */
    private $layout;

    /**
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param string $bundle_name
     *
     * @return $this
     */
    public function setBundleName(string $bundle_name) {
        if(!$this->setModified('bundle_name', $bundle_name)->isModified()) {
            return $this;
        }
        $this->bundle_name = $bundle_name;
		return $this;
    }

    /**
     * @return string
     */
    public function getBundleName() {
        return $this->bundle_name;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(string $code) {
        if(!$this->setModified('code', $code)->isModified()) {
            return $this;
        }
        $this->code = $code;
		return $this;
    }

    /**
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\Layout $layout
     *
     * @return $this
     */
    public function setLayout(\BiberLtd\Bundle\ContentManagementBundle\Entity\Layout $layout) {
        if(!$this->setModified('layout', $layout)->isModified()) {
            return $this;
        }
        $this->layout = $layout;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\Layout
     */
    public function getLayout() {
        return $this->layout;
    }

    /**
     * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout $modules_of_layout
     *
     * @return $this
     */
    public function setModulesOfLayout(\BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout $modules_of_layout) {
        if(!$this->setModified('modules_of_layout', $modules_of_layout)->isModified()) {
            return $this;
        }
        $this->modules_of_layout = $modules_of_layout;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout
     */
    public function getModulesOfLayout() {
        return $this->modules_of_layout;
    }

    /**
     * @param array $navigation_items
     *
     * @return $this
     */
    public function setNavigationItems(array $navigation_items) {
        if(!$this->setModified('navigation_items', $navigation_items)->isModified()) {
            return $this;
        }
        $this->navigation_items = $navigation_items;
		return $this;
		return $this;
    }

    /**
     * @return array
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
        if(!$this->setModified('site', $site)->isModified()) {
            return $this;
        }
        $this->site = $site;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\SiteManagementBundle\Entity\Site
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status) {
        if(!$this->setModified('status', $status)->isModified()) {
            return $this;
        }
        $this->status = $status;
		return $this;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

}