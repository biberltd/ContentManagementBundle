<?php
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
/**
 * @name        Layout
 * @package		BiberLtd\Core\AccessManagementBundle
 *
 * @author		Murat Ünal
 * @version     1.0.2
 * @date        10.10.2013
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
 *     name="layout",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxULayoutId", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idxULayoutCode", columns={"code"})
 *     }
 * )
 */
class Layout extends CoreLocalizableEntity
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
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
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout",
     *     mappedBy="layout"
     * )
     */
    private $modules_of_layout;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page", mappedBy="layout")
     */
    private $pages;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\LayoutLocalization",
     *     mappedBy="layout"
     * )
     */
    protected $localizations;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id")
     */
    private $site;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Theme", inversedBy="layouts")
     * @ORM\JoinColumn(name="theme", referencedColumnName="id", nullable=false)
     */
    private $theme;
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
     * @name                  setHtml ()
     *                                Sets the html property.
     *                                Updates the data only if stored value and value to be set are different.
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
     *                          Returns the value of html property.
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
     * @name                  setPages ()
     *                                 Sets the pages property.
     *                                 Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $pages
     *
     * @return          object                $this
     */
    public function setPages($pages) {
        if(!$this->setModified('pages', $pages)->isModified()) {
            return $this;
        }
        $this->pages = $pages;
		return $this;
    }

    /**
     * @name            getPages ()
     *                           Returns the value of pages property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->pages
     */
    public function getPages() {
        return $this->pages;
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
     * @name                  setTheme ()
     *                                 Sets the theme property.
     *                                 Updates the data only if stored value and value to be set are different.
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
     *                           Returns the value of theme property.
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
}
/**
 * Change Log:
 * **************************************
 * v1.0.2                      Murat Ünal
 * 10.10.2013
 * **************************************
 * A getCode()
 * A getHtml()
 * A getId()
 * A getModulesOfLayout()
 * A getPages()
 * A getSite
 * A getTheme
 *
 * A setCode()
 * A setHtml()
 * A setModulesOfLayout()
 * A setPages()
 * A setSite
 * A setTheme
 *
 */