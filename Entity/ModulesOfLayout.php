<?php
/**
 * @name        ModulesOfLayout
 * @package		BiberLtd\Core\ContentManagementBundle
 *
 * @author		Murat Ünal
 *
 * @version     1.0.0
 * @date        24.09.2013
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
 *     name="modules_of_layout",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={@ORM\Index(name="idx_n_modules_of_layout_section", columns={"section"})},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_modules_of_layout_id", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idx_u_modules_of_layout", columns={"layout","module","page","section"})
 *     }
 * )
 */
class ModulesOfLayout extends CoreLocalizableEntity
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $section;

    /** 
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $sort_order;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $style;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\ModulesOfLayoutLocalization",
     *     mappedBy="modules_of_layout"
     * )
     */
    protected $localizations;

    /** 
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\Layout",
     *     inversedBy="modules_of_layout"
     * )
     * @ORM\JoinColumn(name="layout", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $layout;

    /** 
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\Page",
     *     inversedBy="modules_of_layout"
     * )
     * @ORM\JoinColumn(name="page", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $page;

    /** 
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\Module",
     *     inversedBy="modules_of_layout"
     * )
     * @ORM\JoinColumn(name="module", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $module;
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
     * @name                  setModule ()
     *                                  Sets the module property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $module
     *
     * @return          object                $this
     */
    public function setModule($module) {
        if(!$this->setModified('module', $module)->isModified()) {
            return $this;
        }
        $this->module = $module;
		return $this;
    }

    /**
     * @name            getModule ()
     *                            Returns the value of module property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->module
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * @name                  setPage ()
     *                                Sets the page property.
     *                                Updates the data only if stored value and value to be set are different.
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
        if(!$this->setModified('page', $page)->isModified()) {
            return $this;
        }
        $this->page = $page;
		return $this;
    }

    /**
     * @name            getPage ()
     *                          Returns the value of page property.
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
     * @name                  setSection ()
     *                                   Sets the section property.
     *                                   Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $section
     *
     * @return          object                $this
     */
    public function setSection($section) {
        if(!$this->setModified('section', $section)->isModified()) {
            return $this;
        }
        $this->section = $section;
		return $this;
    }

    /**
     * @name            getSection ()
     *                             Returns the value of section property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->section
     */
    public function getSection() {
        return $this->section;
    }

    /**
     * @name                  setSortOrder ()
     *                                     Sets the sort_order property.
     *                                     Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $sort_order
     *
     * @return          object                $this
     */
    public function setSortOrder($sort_order) {
        if(!$this->setModified('sort_order', $sort_order)->isModified()) {
            return $this;
        }
        $this->sort_order = $sort_order;
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
     * @return          mixed           $this->sort_order
     */
    public function getSortOrder() {
        return $this->sort_order;
    }

    /**
     * @name                  setStyle ()
     *                                 Sets the style property.
     *                                 Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $style
     *
     * @return          object                $this
     */
    public function setStyle($style) {
        if(!$this->setModified('style', $style)->isModified()) {
            return $this;
        }
        $this->style = $style;
		return $this;
    }

    /**
     * @name            getStyle ()
     *                           Returns the value of style property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->style
     */
    public function getStyle() {
        return $this->style;
    }

}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Murat Ünal
 * 24.09.2013
 * **************************************
 * A getId()
 * A getLayout()
 * A getLocalizations()
 * A getModule()
 * A getPage()
 * A getSection()
 * A getSortOrder()
 * A getStyle()
 *
 * A setLayout()
 * A setLocalizations()
 * A setModule()
 * A setPage()
 * A setSection()
 * A setSortOrder()
 * A setStyle()
 *
 */