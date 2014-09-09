<?php
/**
 * @name        Theme
 * @package		BiberLtd\Core\ContentManagementBundle
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
namespace BiberLtd\Core\Bundles\ContentManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Core\CoreLocalizableEntity;
/** 
 * @ORM\Entity
 * @ORM\Table(
 *     name="theme",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={
 *         @ORM\Index(name="idx_n_theme_date_added", columns={"date_added"}),
 *         @ORM\Index(name="idx_n_theme_updated", columns={"date_updated"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_theme_id", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idx_u_theme_folder", columns={"folder"})
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
     * @ORM\Column(type="string", length=1, nullable=false)
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
     * @ORM\Column(type="integer", nullable=false)
     */
    private $count_modules;

    /** 
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count_layouts;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\Layout", mappedBy="theme")
     */
    private $layouts;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\ThemeLocalization",
     *     mappedBy="theme"
     * )
     */
    protected $localizations;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\Module", mappedBy="theme")
     */
    private $modules;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Core\Bundles\SiteManagementBundle\Entity\Site")
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