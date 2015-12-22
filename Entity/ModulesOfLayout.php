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
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=45, nullable=true)
	 * @var string
	 */
	private $section;

	/**
	 * @ORM\Column(type="integer", length=10, nullable=true, options={"default":1})
	 * @var int
	 */
	private $sort_order;

	/**
	 * @ORM\Column(type="string", length=45, nullable=true)
	 * @var string
	 */
	private $style;

	/**
	 * @ORM\ManyToOne(
	 *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Layout",
	 *     inversedBy="modules_of_layout"
	 * )
	 * @ORM\JoinColumn(name="layout", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Layout
	 */
	private $layout;

	/**
	 * @ORM\ManyToOne(
	 *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page",
	 *     inversedBy="modules_of_layout"
	 * )
	 * @ORM\JoinColumn(name="page", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Page
	 */
	private $page;

	/**
	 * @ORM\ManyToOne(
	 *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Module",
	 *     inversedBy="modules_of_layout"
	 * )
	 * @ORM\JoinColumn(name="module", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Module
	 */
	private $module;

	/**
	 * @return mixed
	 */
    public function getId(){
        return $this->id;
    }

	/**
	 * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\Layout $layout
	 *
	 * @return $this
	 */
    public function setLayout(\BiberLtd\Bundle\ContentManagementBundle\Entity\Layout $layout) {
        if(!$this->setModified('layout', $layout)->isModified()) {
            return $this;
        }
        $this->layout = $layout;
		return $this;
    }

	/**
	 * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\Layout
	 */
    public function getLayout() {
        return $this->layout;
    }

	/**
	 * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\Module $module
	 *
	 * @return $this
	 */
    public function setModule(\BiberLtd\Bundle\ContentManagementBundle\Entity\Module $module) {
        if(!$this->setModified('module', $module)->isModified()) {
            return $this;
        }
        $this->module = $module;
		return $this;
    }

	/**
	 * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\Module
	 */
    public function getModule() {
        return $this->module;
    }

	/**
	 * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\Page $page
	 *
	 * @return $this
	 */
    public function setPage(\BiberLtd\Bundle\ContentManagementBundle\Entity\Page $page) {
        if(!$this->setModified('page', $page)->isModified()) {
            return $this;
        }
        $this->page = $page;
		return $this;
    }

	/**
	 * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\Page
	 */
    public function getPage() {
        return $this->page;
    }

	/**
	 * @param string $section
	 *
	 * @return $this
	 */
    public function setSection(\string $section) {
        if(!$this->setModified('section', $section)->isModified()) {
            return $this;
        }
        $this->section = $section;
		return $this;
    }

	/**
	 * @return string
	 */
    public function getSection() {
        return $this->section;
    }

	/**
	 * @param int $sort_order
	 *
	 * @return $this
	 */
    public function setSortOrder(\integer $sort_order) {
        if(!$this->setModified('sort_order', $sort_order)->isModified()) {
            return $this;
        }
        $this->sort_order = $sort_order;
		return $this;
    }

	/**
	 * @return int
	 */
    public function getSortOrder() {
        return $this->sort_order;
    }

	/**
	 * @param string $style
	 *
	 * @return $this
	 */
    public function setStyle(\string $style) {
        if(!$this->setModified('style', $style)->isModified()) {
            return $this;
        }
        $this->style = $style;
		return $this;
    }

	/**
	 * @return string
	 */
    public function getStyle() {
        return $this->style;
    }
}