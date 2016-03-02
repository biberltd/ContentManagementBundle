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
     * @var int
     */
    private $id;

    /** 
     * @ORM\Column(type="string", unique=true, length=45, nullable=false)
     * @var string
     */
    private $code;

    /** 
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $html;

    /** 
     * @ORM\Column(type="string", length=155, nullable=true)
     * @var string
     */
    private $bundle_name;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     * @var string
     */
    private $bundle_folder;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout", mappedBy="module")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\ModulesOfLayout
     */
    private $modules_of_layout;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\ModuleLocalization",
     *     mappedBy="module"
     * )
     * @var array
     */
    protected $localizations;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Theme", inversedBy="modules")
     * @ORM\JoinColumn(name="theme", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Theme
     */
    private $theme;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", nullable=false, onDelete="CASCADE")
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
     * @param string $html
     *
     * @return $this
     */
    public function setHtml(string $html) {
        if(!$this->setModified('html', $html)->isModified()) {
            return $this;
        }
        $this->html = $html;
		return $this;
    }

    /**
     * @return string
     */
    public function getHtml() {
        return $this->html;
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
     * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\Theme $theme
     *
     * @return $this
     */
    public function setTheme(\BiberLtd\Bundle\ContentManagementBundle\Entity\Theme $theme) {
        if(!$this->setModified('theme', $theme)->isModified()) {
            return $this;
        }
        $this->theme = $theme;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\Theme
     */
    public function getTheme() {
        return $this->theme;
    }

    /**
     * @param string $bundle_folder
     *
     * @return $this
     */
    public function setBundleFolder(string $bundle_folder) {
        if($this->setModified('bundle_folder', $bundle_folder)->isModified()) {
            $this->bundle_folder = $bundle_folder;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBundleFolder() {
        return $this->bundle_folder;
    }

}