<?php
/**
 * @name        NavigationItem
 * @package		BiberLtd\Core\ContentManagementBundle
 *
 * @author		Can Berkol
 *
 * @version     1.0.2
 * @date        06.01.2014
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Core\CoreLocalizableEntity;
/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="navigation_item",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idx_u_navigation_item_id", columns={"id"})}
 * )
 */
class NavigationItem extends CoreLocalizableEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    private $target;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $sort_order;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    private $is_child;

    /** 
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem",
     *     mappedBy="parent"
     * )
     */
    private $navigation_items;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItemLocalization",
     *     mappedBy="navigation_item"
     * )
     */
    protected $localizations;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page",
     *     inversedBy="navigation_items"
     * )
     * @ORM\JoinColumn(name="page", referencedColumnName="id", onDelete="RESTRICT")
     */
    private $page;


    /** 
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem",
     *     inversedBy="navigation_items"
     * )
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;


    /**
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation",
     *     inversedBy="navigation_items"
     * )
     * @ORM\JoinColumn(name="navigation", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $navigation;
    /******************************************************************
     * PUBLIC SET AND GET FUNCTIONS                                   *
     ******************************************************************/

    /**
     * @name            getId()
     *  				Gets $id property.
     * .
     * @author          Can Berkol
     * @since			1.0.0
     * @version         1.0.0
     *
     * @return          string          $this->id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @name            setIsChild()
     *                  Sets the is_child property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           string $is_child
     *
     * @return          object                $this
     */
    public function setIsChild($is_child) {
        if($this->setModified('is_child', $is_child)->isModified()) {
            $this->is_child = $is_child;
        }
        return $this;
    }

    /**
     * @name            getIsChild()
     *                  Returns the value of is_child property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          bool
     */
    public function getIsChild() {
        return $this->is_child;
    }

    /**
     * @name            isChild()
     *                  Alias of getIsChild()
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          bool
     */
    public function isChild() {
        return $this->is_child;
    }

    /**
     * @name            setNavigation ()
     *                  Sets the navigation property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $navigation
     *
     * @return          object                $this
     */
    public function setNavigation($navigation) {
        if($this->setModified('navigation', $navigation)->isModified()) {
            $this->navigation = $navigation;
        }
        return $this;
    }

    /**
     * @name            getNavigation ()
     *                  Returns the value of navigation property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->navigation
     */
    public function getNavigation() {
        return $this->navigation;
    }

    /**
     * @name            setParent()
     *                  Sets the navigation_item property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $parent
     *
     * @return          object                $this
     */
    public function setParent($navigation_item) {
        if($this->setModified('parent', $parent)->isModified()) {
            $this->parent = $parent;
        }
        return $this;
    }

    /**
     * @name            getParent()
     *                  Returns the value of navigation_item property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->navigation_item
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @name            setNavigationItems()
     *                  Sets the navigation_items property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
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
     * @version         1.0.0
     *
     * @return          mixed           $this->navigation_items
     */
    public function getNavigationItems() {
        return $this->navigation_items;
    }

    /**
     * @name            setPage ()
     *                  Sets the page property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $page
     *
     * @return          object                $this
     */
    public function setPage($page) {
        if($this->setModified('page', $page)->isModified()) {
            $this->page = $page;
        }
        return $this;
    }

    /**
     * @name            getPage ()
     *                  Returns the value of page property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @name            setSortOrder ()
     *                  Sets the sort_order property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           integer $sort_order
     *
     * @return          object                $this
     */
    public function setSortOrder($sort_order) {
        if($this->setModified('sort_order', $sort_order)->isModified()) {
            $this->sort_order = $sort_order;
        }
        return $this;
    }

    /**
     * @name            getSortOrder ()
     *                               Returns the value of sort_order property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          integer           $this->sort_order
     */
    public function getSortOrder() {
        return $this->sort_order;
    }

    /**
     * @name                  setTarget ()
     *                                  Sets the target property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           string                $target
     *
     * @return          object                $this
     */
    public function setTarget($target) {
        if($this->setModified('target', $target)->isModified()) {
            $this->target = $target;
        }
        return $this;
    }

    /**
     * @name            getTarget ()
     *                  Returns the value of target property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          string           $this->target
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @name            setUrl ()
     *                  Sets the url property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           string                  $url
     *
     * @return          object                  $this
     */
    public function setUrl($url) {
        if($this->setModified('url', $url)->isModified()) {
            $this->url = $url;
        }
        return $this;
    }

    /**
     * @name            getUrl ()
     *                         Returns the value of url property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->url
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @name            setIcon()
     *                  Sets the icon property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $icon
     *
     * @return          object                $this
     */
    public function setIcon($icon) {
        if($this->setModified('icon', $icon)->isModified()) {
            $this->icon = $icon;
        }

        return $this;
    }

    /**
     * @name            getIcon()
     *                  Returns the value of icon property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.1
     * @version         1.0.1
     *
     * @return          mixed           $this->icon
     */
    public function getIcon() {
        return $this->icon;
    }

}
/**
 * Change Log:
 * **************************************
 * v1.0.2                      Can Berkol
 * 06.01.2014
 * **************************************
 * A getParent()
 * A setParent()
 * D getNavigationItem()
 * D setNavigationItem()
 *
 * **************************************
 * v1.0.1                      Can Berkol
 * 05.01.2014
 * **************************************
 * A getIcon()
 * A isChild()
 * A setIcon()
 *
 * **************************************
 * v1.0.0                      Can Berkol
 * 24.09.2013
 * **************************************
 * A getId()
 * A getIsChild()
 * A getLocalizations()
 * A getNavigation()
 * A getNavigationItem()
 * A getNavigationItems()
 * A getPage()
 * A getSortOrder()
 * A getTarget()
 * A getUrl()
 * A setIsChild()
 * A setLocalizations()
 * A setNavigation()
 * A setNavigationItem()
 * A setNavigationItems()
 * A setPage()
 * A setSortOrder()
 * A setTarget()
 * A setUrl()
 *
 */