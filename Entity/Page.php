<?php
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
/**
 * @name        page
 * @package		BiberLtd\Core\ContentManagementBundle
 *
 * @author		Murat Ünal
 * @version     1.0.3
 * @date        21.11.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Core\CoreLocalizableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="page",
 *     options={"charset":"utf8","collate":"utf8_Turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_page_id", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idx_u_page_code", columns={"code"})
 *     }
 * )
 */
class Page extends CoreLocalizableEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=15)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=false)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $bundle_name;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout",
     *     mappedBy="page"
     * )
     */
    private $modules_of_layout;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem",
     *     mappedBy="page"
     * )
     */
    private $navigation_items;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\PageLocalization",
     *     mappedBy="page"
     * )
     */
    protected $localizations;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Layout", inversedBy="pages")
     * @ORM\JoinColumn(name="layout", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $layout;
    /******************************************************************
     * PUBLIC SET AND GET FUNCTIONS                                   *
     ******************************************************************/

    /**
     * @name            getId()
     *  				Gets $id property.
     * .
     * @author          Murat Ünal
     * @since			1.0.0
     * @version         1.0.0
     *
     * @return          string          $this->id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @name                  setBundleName ()
     *                                      Sets the bundle_name property.
     *                                      Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $bundle_name
     *
     * @return          object                $this
     */
    public function setBundleName($bundle_name) {
        if(!$this->setModified('bundle_name', $bundle_name)->isModified()) {
            return $this;
        }
        $this->bundle_name = $bundle_name;
		return $this;
    }

    /**
     * @name            getBundleName ()
     *                                Returns the value of bundle_name property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->bundle_name
     */
    public function getBundleName() {
        return $this->bundle_name;
    }

    /**
     * @name                  setCode ()
     *                                Sets the code property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $code
     *
     * @return          object                $this
     */
    public function setCode($code) {
        if(!$this->setModified('code', $code)->isModified()) {
            return $this;
        }
        $this->code = $code;
		return $this;
    }

    /**
     * @name            getCode ()
     *                          Returns the value of code property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->code
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @name                  setLayout ()
     *                                  Sets the layout property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $layout
     *
     * @return          object                $this
     */
    public function setLayout($layout) {
        if(!$this->setModified('layout', $layout)->isModified()) {
            return $this;
        }
        $this->layout = $layout;
		return $this;
    }

    /**
     * @name            getLayout ()
     *                            Returns the value of layout property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->layout
     */
    public function getLayout() {
        return $this->layout;
    }

    /**
     * @name                  setModulesOfLayout ()
     *                                           Sets the modules_of_layout property.
     *                                           Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $modules_of_layout
     *
     * @return          object                $this
     */
    public function setModulesOfLayout($modules_of_layout) {
        if(!$this->setModified('modules_of_layout', $modules_of_layout)->isModified()) {
            return $this;
        }
        $this->modules_of_layout = $modules_of_layout;
		return $this;
    }

    /**
     * @name            getModulesOfLayout ()
     *                                     Returns the value of modules_of_layout property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->modules_of_layout
     */
    public function getModulesOfLayout() {
        return $this->modules_of_layout;
    }

    /**
     * @name            setNavigation İtems()
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
     * @param           array               $navigation_items
     *
     * @return          object              $this
     */
    public function setNavigationItems($navigation_items) {
        if(!$this->setModified('navigation_items', $navigation_items)->isModified()) {
            return $this;
        }
        $this->navigation_items = $navigation_items;
		return $this;
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
     * @return          array           $this->navigation_items
     */
    public function getNavigationItems() {
        return $this->navigation_items;
    }

    /**
     * @name                  setSite ()
     *                                Sets the site property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $site
     *
     * @return          object                $this
     */
    public function setSite($site) {
        if(!$this->setModified('site', $site)->isModified()) {
            return $this;
        }
        $this->site = $site;
		return $this;
    }

    /**
     * @name            getSite ()
     *                          Returns the value of site property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->site
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * @name                  setStatus ()
     *                                  Sets the status property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $status
     *
     * @return          object                $this
     */
    public function setStatus($status) {
        if(!$this->setModified('status', $status)->isModified()) {
            return $this;
        }
        $this->status = $status;
		return $this;
    }

    /**
     * @name            getStatus ()
     *                            Returns the value of status property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->status
     */
    public function getStatus() {
        return $this->status;
    }

}
/**
 * Change Log:
 * **************************************
 * v1.0.3                      Can Berkol
 * 21.11.2013
 * **************************************
 * A getBundleName
 * A setBundleName
 *
 * **************************************
 * v1.0.2                     M urat Ünal
 * 10.10.2013
 * **************************************
 * A getCode()
 * A getFilesOfPage()
 * A getId()
 * A getLayout()
 * A getLocalizations()
 * A getModulesOfLayout()
 * A getNavigationItems()
 * A getSite()
 * A getStatus()
 *
 * A setCode()
 * A setFilesOfPage()
 * A setLayout()
 * A setLocalizations()
 * A setModulesOfLayout()
 * A setNavigationItems()
 * A setSite()
 * A setStatus()
 *
 */