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
 *     name="navigation_item",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idxUNavigationItemId", columns={"id"})}
 * )
 */
class NavigationItem extends CoreLocalizableEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @var string
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=1, nullable=false, options={"default":"b"})
     * @var string
     */
    private $target;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false, options={"default":1})
     * @var int
     */
    private $sort_order;

    /**
     * @ORM\Column(type="string", length=1, nullable=false, options={"default":"n"})
     * @var string
     */
    private $is_child;

    /** 
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem", mappedBy="parent")
     * @var array
     */
    private $navigation_items;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItemLocalization",
     *     mappedBy="navigation_item"
     * )
     * @var array
     */
    protected $localizations;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page",
     *     inversedBy="navigation_items"
     * )
     * @ORM\JoinColumn(name="page", referencedColumnName="id", onDelete="RESTRICT")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Page
     */
    private $page;


    /** 
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem",
     *     inversedBy="navigation_items"
     * )
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", onDelete="CASCADE")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem
     */
    private $parent;


    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation", inversedBy="items")
     * @ORM\JoinColumn(name="navigation", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem
     */
    private $navigation;

    /**
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param string $is_child
     *
     * @return $this
     */
    public function setIsChild(string $is_child) {
        if($this->setModified('is_child', $is_child)->isModified()) {
            $this->is_child = $is_child;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getIsChild() {
        return $this->is_child;
    }

    /**
     * @return string
     */
    public function isChild() {
        return $this->is_child;
    }

    /**
     * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation $navigation
     *
     * @return $this
     */
    public function setNavigation(\BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation $navigation) {
        if($this->setModified('navigation', $navigation)->isModified()) {
            $this->navigation = $navigation;
        }
        return $this;
    }

    /**
     * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem
     */
    public function getNavigation() {
        return $this->navigation;
    }

    /**
     * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem $navigation_item
     *
     * @return $this
     */
    public function setParent(\BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem $navigation_item) {
        if($this->setModified('parent', $parent)->isModified()) {
            $this->parent = $parent;
        }
        return $this;
    }

    /**
     * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param array $navigation_items
     *
     * @return $this
     */
    public function setNavigationItems(array $navigation_items) {
        if($this->setModified('navigation_items', $navigation_items)->isModified()) {
            $this->navigation_items = $navigation_items;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getNavigationItems() {
        return $this->navigation_items;
    }

    /**
     * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\Page $page
     *
     * @return $this
     */
    public function setPage(\BiberLtd\Bundle\ContentManagementBundle\Entity\Page $page) {
        if($this->setModified('page', $page)->isModified()) {
            $this->page = $page;
        }
        return $this;
    }

    /**
     * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\Page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @param int $sort_order
     *
     * @return $this
     */
    public function setSortOrder(int $sort_order) {
        if($this->setModified('sort_order', $sort_order)->isModified()) {
            $this->sort_order = $sort_order;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getSortOrder() {
        return $this->sort_order;
    }

    /**
     * @param string $target
     *
     * @return $this
     */
    public function setTarget(string $target) {
        if($this->setModified('target', $target)->isModified()) {
            $this->target = $target;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url) {
        if($this->setModified('url', $url)->isModified()) {
            $this->url = $url;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $icon
     *
     * @return $this
     */
    public function setIcon(string $icon) {
        if($this->setModified('icon', $icon)->isModified()) {
            $this->icon = $icon;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

}