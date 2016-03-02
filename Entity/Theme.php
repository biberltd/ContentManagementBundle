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
     * @var int
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     * @var string
     */
    private $folder;

    /** 
     * @ORM\Column(type="string", length=1, nullable=false, options={"default":"f"})
     * @var string
     */
    private $type;

    /** 
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    public $date_added;

    /** 
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    public $date_updated;

    /** 
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     * @var int
     */
    private $count_modules;

    /** 
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     * @var int
     */
    private $count_layouts;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
	 */
	public $date_removed;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Layout", mappedBy="theme")
     * @var array
     */
    private $layouts;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ThemeLocalization",
     *     mappedBy="theme"
     * )
     * @var array
     */
    protected $localizations;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Module", mappedBy="theme")
     * @var array
     */
    private $modules;

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
     * @param int $count_layouts
     *
     * @return $this
     */
    public function setCountLayouts(\int $count_layouts) {
        if(!$this->setModified('count_layouts', $count_layouts)->isModified()) {
            return $this;
        }
        $this->count_layouts = $count_layouts;
		return $this;
    }

    /**
     * @return mixed
     */
    public function getCountLayouts() {
        return $this->count_layouts;
    }

    /**
     * @param int $count_modules
     *
     * @return $this
     */
    public function setCountModules(int $count_modules) {
        if(!$this->setModified('count_modules', $count_modules)->isModified()) {
            return $this;
        }
        $this->count_modules = $count_modules;
		return $this;
    }

    /**
     * @return int
     */
    public function getCountModules() {
        return $this->count_modules;
    }

    /**
     * @param string $folder
     *
     * @return $this
     */
    public function setFolder(string $folder) {
        if(!$this->setModified('folder', $folder)->isModified()) {
            return $this;
        }
        $this->folder = $folder;
		return $this;
    }

    /**
     * @return string
     */
    public function getFolder() {
        return $this->folder;
    }

    /**
     * @param array $layouts
     *
     * @return $this
     */
    public function setLayouts(array $layouts) {
        if(!$this->setModified('layouts', $layouts)->isModified()) {
            return $this;
        }
        $this->layouts = $layouts;
		return $this;
    }

    /**
     * @return array
     */
    public function getLayouts() {
        return $this->layouts;
    }

    /**
     * @param array $modules
     *
     * @return $this
     */
    public function setModules(array $modules) {
        if(!$this->setModified('modules', $modules)->isModified()) {
            return $this;
        }
        $this->modules = $modules;
		return $this;
    }

    /**
     * @return array
     */
    public function getModules() {
        return $this->modules;
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
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type) {
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