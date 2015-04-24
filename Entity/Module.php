<?php
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;

/**
 * @name        module
 * @package		BiberLtd\Bundle\CoreBundle\AccessManagementBundle
 *
 * @author      Can Berkol
 * @author		Murat Ünal
 * @version     1.0.5
 * @date        29.04.2014
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreLocalizableEntity;
/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="module",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUModuleId", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idxUModuleCode", columns={"code"})
 *     }
 * )
 */
class Module extends CoreLocalizableEntity
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** 
     * @ORM\Column(type="string", unique=true, length=45, nullable=false)
     */
    private $code;

    /** 
     * @ORM\Column(type="text", nullable=true)
     */
    private $html;

    /** 
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $bundle_name;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $bundle_folder;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout",
     *     mappedBy="module"
     * )
     */
    private $modules_of_layout;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ModuleLocalization",
     *     mappedBy="module"
     * )
     */
    protected $localizations;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Theme", inversedBy="modules")
     * @ORM\JoinColumn(name="theme", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $theme;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="CASCADE")
     */
    private $site;
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
     * @return          integer          $this->id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @name            setBundleName ()
     *                  Sets the bundle_name property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.3
     * @version         1.0.3
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
     *                  Returns the value of bundle_name property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.3
     * @version         1.0.3
     *
     * @return          mixed           $this->bundle_name
     */
    public function getBundleName() {
        return $this->bundle_name;
    }

    /**
     * @name            setCode ()
     *                  Sets the code property.
     *                  Updates the data only if stored value and value to be set are different.
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
     *                  Returns the value of code property.
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
     * @name            setHtml ()
     *                  Sets the html property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $html
     *
     * @return          object                $this
     */
    public function setHtml($html) {
        if(!$this->setModified('html', $html)->isModified()) {
            return $this;
        }
        $this->html = $html;
		return $this;
    }

    /**
     * @name            getHtml ()
     *                  Returns the value of html property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->html
     */
    public function getHtml() {
        return $this->html;
    }

    /**
     * @name            setModulesOfLayout ()
     *                  Sets the modules_of_layout property.
     *                  Updates the data only if stored value and value to be set are different.
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
     *                  Returns the value of modules_of_layout property.
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
     * @name            setSite ()
     *                  Sets the site property.
     *                  Updates the data only if stored value and value to be set are different.
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
     *                  Returns the value of site property.
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
     * @name            setTheme ()
     *                  Sets the theme property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $theme
     *
     * @return          object                $this
     */
    public function setTheme($theme) {
        if(!$this->setModified('theme', $theme)->isModified()) {
            return $this;
        }
        $this->theme = $theme;
		return $this;
    }

    /**
     * @name            getTheme ()
     *                  Returns the value of theme property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->theme
     */
    public function getTheme() {
        return $this->theme;
    }

    /**
     * @name            setBundleFolder ()
     *                  Sets the bundle_folder property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.4
     * @version         1.0.4
     *
     * @use             $this->setModified()
     *
     * @param           mixed $bundle_folder
     *
     * @return          object                $this
     */
    public function setBundleFolder($bundle_folder) {
        if($this->setModified('bundle_folder', $bundle_folder)->isModified()) {
            $this->bundle_folder = $bundle_folder;
        }

        return $this;
    }

    /**
     * @name            getBundleFolder ()
     *                  Returns the value of bundle_folder property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.4
     * @version         1.0.4
     *
     * @return          mixed           $this->bundle_folder
     */
    public function getBundleFolder() {
        return $this->bundle_folder;
    }

}
/**
 * Change Log:
 * **************************************
 * v1.0.34                     Can Berkol
 * 29.04.2014
 * **************************************
 * A getBundleFolder()
 * A setBundleFolder()
 *
 * **************************************
 * v1.0.3                     Can Berkol
 * 21.11.2013
 * **************************************
 * A getBundleName()
 * A setBundleName()
 *
 * **************************************
 * v1.0.2                      Murat Ünal
 * 10.10.2013
 * **************************************
 * A getCode()
 * A getHtml()
 * A getId()
 * A getLocalizations()
 * A getModulesOfLayout()
 * A getSite()
 * A getTheme()
 * A setCode()
 * A setHtml()
 * A setLocalizations()
 * A setModulesOfLayout()
 * A setSite()
 * A setTheme()
 *
 */