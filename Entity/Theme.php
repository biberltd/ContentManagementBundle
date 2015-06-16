<?php

/**
 * @name        Theme
 * @package		BiberLtd\Bundle\CoreBundle\ContentManagementBundle
 *
 * @author		Murat Ünal
 *
 * @version     1.0.1
 * @date        10.10.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreLocalizableEntity;
/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="theme",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={
 *         @ORM\Index(name="idxNThemeDateAdded", columns={"date_added"}),
 *         @ORM\Index(name="idxNThemeDateUpdated", columns={"date_updated"}),
 *         @ORM\Index(name="idxUThemeDateRemoved", columns={"date_removed"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUThemeId", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idxUThemeFolder", columns={"folder"})
 *     }
 * )
 */
class Theme extends CoreLocalizableEntity
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
    private $folder;

    /** 
     * @ORM\Column(type="string", length=1, nullable=false, options={"default":"f"})
     */
    private $type;

    /** 
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $date_added;

    /** 
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $date_updated;

    /** 
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     */
    private $count_modules;

    /** 
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    private $count_layouts;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	public $date_removed;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Layout", mappedBy="theme")
     */
    private $layouts;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ThemeLocalization",
     *     mappedBy="theme"
     * )
     */
    protected $localizations;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Module", mappedBy="theme")
     */
    private $modules;

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
     *                  Gets $id property.
     * .
     * @author          Murat Ünal
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          integer          $this->id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @name                  setCountLayouts ()
     *                                        Sets the count_layouts property.
     *                                        Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $count_layouts
     *
     * @return          object                $this
     */
    public function setCountLayouts($count_layouts) {
        if(!$this->setModified('count_layouts', $count_layouts)->isModified()) {
            return $this;
        }
        $this->count_layouts = $count_layouts;
		return $this;
    }

    /**
     * @name            getCountLayouts ()
     *                                  Returns the value of count_layouts property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->count_layouts
     */
    public function getCountLayouts() {
        return $this->count_layouts;
    }

    /**
     * @name                  setCountModules ()
     *                                        Sets the count_modules property.
     *                                        Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $count_modules
     *
     * @return          object                $this
     */
    public function setCountModules($count_modules) {
        if(!$this->setModified('count_modules', $count_modules)->isModified()) {
            return $this;
        }
        $this->count_modules = $count_modules;
		return $this;
    }

    /**
     * @name            getCountModules ()
     *                                  Returns the value of count_modules property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->count_modules
     */
    public function getCountModules() {
        return $this->count_modules;
    }

    /**
     * @name                  setFolder ()
     *                                  Sets the folder property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $folder
     *
     * @return          object                $this
     */
    public function setFolder($folder) {
        if(!$this->setModified('folder', $folder)->isModified()) {
            return $this;
        }
        $this->folder = $folder;
		return $this;
    }

    /**
     * @name            getFolder ()
     *                            Returns the value of folder property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->folder
     */
    public function getFolder() {
        return $this->folder;
    }

    /**
     * @name                  setLayouts ()
     *                                   Sets the layouts property.
     *                                   Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $layouts
     *
     * @return          object                $this
     */
    public function setLayouts($layouts) {
        if(!$this->setModified('layouts', $layouts)->isModified()) {
            return $this;
        }
        $this->layouts = $layouts;
		return $this;
    }

    /**
     * @name            getLayouts ()
     *                             Returns the value of layouts property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->layouts
     */
    public function getLayouts() {
        return $this->layouts;
    }

    /**
     * @name                  setModules ()
     *                                   Sets the modules property.
     *                                   Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $modules
     *
     * @return          object                $this
     */
    public function setModules($modules) {
        if(!$this->setModified('modules', $modules)->isModified()) {
            return $this;
        }
        $this->modules = $modules;
		return $this;
    }

    /**
     * @name            getModules ()
     *                             Returns the value of modules property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->modules
     */
    public function getModules() {
        return $this->modules;
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
     * @name                  setType ()
     *                                Sets the type property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $type
     *
     * @return          object                $this
     */
    public function setType($type) {
        if(!$this->setModified('type', $type)->isModified()) {
            return $this;
        }
        $this->type = $type;
		return $this;
    }

    /**
     * @name            getType ()
     *                          Returns the value of type property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->type
     */
    public function getType() {
        return $this->type;
    }

}
/**
 * Change Log:
 * **************************************
 * v1.0.1                     Murat Ünal
 * 10.10.2013
 * **************************************
 * A getCountLayouts()
 * A getCountModules()
 * A getDateAdded()
 * A getDateUpdated()
 * A getFolder()
 * A getId()
 * A getLayout()
 * A getLocalizations()
 * A getModules()
 * A getSite()
 * A getType()
 *
 * A setCountLayouts()
 * A setCountModules()
 * A set_date_added()
 * A setDateUpdated()
 * A setFolder()
 * A setLayout()
 * A setLocalizations()
 * A setModules()
 * A setSite()
 * A setType()
 *
 */