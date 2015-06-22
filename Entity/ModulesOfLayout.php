<?php

/**
 * @name        ModulesOfLayout
 * @package		BiberLtd\Bundle\CoreBundle\ContentManagementBundle
 *
 * @author		cAN bERKOL
 * @author		Murat Ünal
 *
 * @version     1.0.1
 * @date        26.05.2015
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
 *     name="modules_of_layout",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={@ORM\Index(name="idxNSectionOfLayout", columns={"section"})},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUModulesOfLayoutId", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idxUModulesOfLayout", columns={"layout","module","page","section"})
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
	 * @ORM\Column(type="integer", length=10, nullable=true, options={"default":1})
	 */
	private $sort_order;

	/**
	 * @ORM\Column(type="string", length=45, nullable=true)
	 */
	private $style;

	/**
	 * @ORM\ManyToOne(
	 *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Layout",
	 *     inversedBy="modules_of_layout"
	 * )
	 * @ORM\JoinColumn(name="layout", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	private $layout;

	/**
	 * @ORM\ManyToOne(
	 *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page",
	 *     inversedBy="modules_of_layout"
	 * )
	 * @ORM\JoinColumn(name="page", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	private $page;

	/**
	 * @ORM\ManyToOne(
	 *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Module",
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
     * @name            setLayout ()
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
     * @name            setModule ()
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
     * @name            setPage ()
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
     * @name            setSection ()
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
     * @name            setSortOrder ()
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
     * @name            setStyle ()
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
 * v1.0.1                      26.05.2015
 * Can Berkol
 * **************************************
 * BF :: Entity name spaces in annotations have been fixed.
 *
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