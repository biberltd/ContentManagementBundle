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
/**
 * @name        FilesOfPage
 * @package		BiberLtd\ContentManagementBundle
 *
 * @author		Can Berkol
 *              Murat Ünal
 * @version     1.0.5
 * @date        26.05.2015
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 */
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreEntity;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="files_of_page",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={
 *         @ORM\Index(name="idxNFilesOfPageDateAdded", columns={"date_added"}),
 *         @ORM\Index(name="idxNFilesOfPageDateUpdated", columns={"date_updated"}),
 *         @ORM\Index(name="idxNFilesOfPageDateRemoved", columns={"date_removed"})
 *     },
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idxUFileOfPage", columns={"file","page"})}
 * )
 */
class FilesOfPage extends CoreEntity
{
	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 * @var \DateTime
	 */
	public $date_added;

	/**
	 * @ORM\Column(type="integer", length=10, nullable=false, options={"default":0})
	 * @var int
	 */
	private $count_view;

	/**
	 * @ORM\Column(type="integer", length=10, nullable=false, options={"default":1})
	 * @var int
	 */
	private $sort_order;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 * @var \DateTime
	 */
	public $date_updated;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var \DateTime
	 */
	public $date_removed;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\FileManagementBundle\Entity\File")
	 * @ORM\JoinColumn(name="file", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 * @var \BiberLtd\Bundle\FileManagementBundle\Entity\File
	 */
	private $file;

	/**
	 * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
	 * @ORM\JoinColumn(name="language", referencedColumnName="id", onDelete="CASCADE")
	 * @var \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language
	 */
	private $language;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page")
	 * @ORM\JoinColumn(name="page", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Page
	 */
	private $page;

	/**
	 * @param int $count_view
	 *
	 * @return $this
	 */
	public function setCountView(\integer $count_view) {
		if(!$this->setModified('count_view', $count_view)->isModified()) {
			return $this;
		}
		$this->count_view = $count_view;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCountView() {
		return $this->count_view;
	}

	/**
	 * @param \BiberLtd\Bundle\FileManagementBundle\Entity\File $file
	 *
	 * @return $this
	 */
    public function setFile(\BiberLtd\Bundle\FileManagementBundle\Entity\File $file) {
        if(!$this->setModified('file', $file)->isModified()) {
            return $this;
        }
        $this->file = $file;
		return $this;
    }

	/**
	 * @return \BiberLtd\Bundle\FileManagementBundle\Entity\File
	 */
    public function getFile() {
        return $this->file;
    }

	/**
	 * @param \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language $language
	 *
	 * @return $this
	 */
    public function setLanguage(\BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language $language) {
        if(!$this->setModified('language', $language)->isModified()) {
            return $this;
        }
        $this->language = $language;
		return $this;
    }

	/**
	 * @return \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language
	 */
    public function getLanguage() {
        return $this->language;
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
}