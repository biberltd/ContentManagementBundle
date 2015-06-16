<?php

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
	 */
	public $date_added;

	/**
	 * @ORM\Column(type="integer", length=10, nullable=false, options={"default":0})
	 */
	private $count_view;

	/**
	 * @ORM\Column(type="integer", length=10, nullable=false, options={"default":1})
	 */
	private $sort_order;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	public $date_updated;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	public $date_removed;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\FileManagementBundle\Entity\File")
	 * @ORM\JoinColumn(name="file", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	private $file;

	/**
	 * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
	 * @ORM\JoinColumn(name="language", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $language;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page")
	 * @ORM\JoinColumn(name="page", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	private $page;

	/**
	 * @name            setCountView ()
	 *                  Sets the count_view property.
	 *                  Updates the data only if stored value and value to be set are different.
	 *
	 * @author          Can Berkol
	 *
	 * @since           1.0.0
	 * @version         1.0.0
	 *
	 * @use             $this->setModified()
	 *
	 * @param           mixed $count_view
	 *
	 * @return          object                $this
	 */
	public function setCountView($count_view) {
		if(!$this->setModified('count_view', $count_view)->isModified()) {
			return $this;
		}
		$this->count_view = $count_view;
		return $this;
	}

	/**
	 * @name            getCountView ()
	 *                  Returns the value of count_view property.
	 *
	 * @author          Can Berkol
	 *
	 * @since           1.0.0
	 * @version         1.0.0
	 *
	 * @return          mixed           $this->count_view
	 */
	public function getCountView() {
		return $this->count_view;
	}

    /**
     * @name                  setFile ()
     *                                Sets the file property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $file
     *
     * @return          object                $this
     */
    public function setFile($file) {
        if(!$this->setModified('file', $file)->isModified()) {
            return $this;
        }
        $this->file = $file;
		return $this;
    }

    /**
     * @name            getFile ()
     *                          Returns the value of file property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->file
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @name                  setLanguage ()
     *                                    Sets the language property.
     *                                    Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $language
     *
     * @return          object                $this
     */
    public function setLanguage($language) {
        if(!$this->setModified('language', $language)->isModified()) {
            return $this;
        }
        $this->language = $language;
		return $this;
    }

    /**
     * @name            getLanguage ()
     *                              Returns the value of language property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->language
     */
    public function getLanguage() {
        return $this->language;
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
}
/**
 * Change Log:
 * **************************************
 * v1.0.5                      26.05.2015
 * Can Berkol
 * **************************************
 * BF :: Entity name spaces in annotations have been fixed.
 *
 * **************************************
 * v1.0.4					   24.04.2015
 * TW #
 * Can Berkol
 * **************************************
 * date_updated & date_Removed properties added.
 *
 * **************************************
 * v1.0.3                     Murat Ünal
 * 10.10.2013
 * **************************************
 * A getCountView()
 * A setCountView()
 * A getDateAdded()
 * A setDateAdded()
 * A getFile()
 * A setFile()
 * A getLanguage()
 * A setLanguage()
 * A getPage()
 * A setPage()
 * A getSortOrder()
 * A setSortOrder()
 *
 */