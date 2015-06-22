<?php

/**
 * @name        PageRevision
 * @package		BiberLtd\ContentManagementBundle
 *
 * @author		Can Berkol
 * @version     1.0.1
 * @date        16.06.2015
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 */

namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
use BiberLtd\Bundle\CoreBundle\CoreEntity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="page_revision",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idxUPageRevision", columns={"revision_number","page","language"})}
 * )
 */
class PageRevision extends CoreEntity
{
	/**
	 * @ORM\Column(type="string", length=155, nullable=false)
	 */
	private $title;

	/**
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $url_key;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $content;

	/**
	 * @ORM\Column(type="string", length=155, nullable=true)
	 */
	private $meta_tilte;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $meta_description;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $meta_keywords;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $revision_number;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	public $date_added;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	public $date_updated;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	public $date_removed;

	/**
	 * @ORM\Column(type="string", length=1, nullable=true, options={"default":"w"})
	 */
	private $status;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page")
	 * @ORM\JoinColumn(name="page", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	private $page;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
	 * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	private $language;

	/**
	 * @name    getContent ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @name    setContent ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param mixed $content
	 *
	 * @return $this
	 */
	public function setContent($content) {
		if (!$this->setModified('content', $content)->isModified()) {
			return $this;
		}
		$this->content = $content;

		return $this;
	}

	/**
	 * @name    getLanguage ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * @name    setLanguage ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param mixed $language
	 *
	 * @return $this
	 */
	public function setLanguage($language) {
		if (!$this->setModified('language', $language)->isModified()) {
			return $this;
		}
		$this->language = $language;

		return $this;
	}

	/**
	 * @name    getMetaDescription ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getMetaDescription() {
		return $this->meta_description;
	}

	/**
	 * @name    setMetaDescription ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param mixed $meta_description
	 *
	 * @return $this
	 */
	public function setMetaDescription($meta_description) {
		if (!$this->setModified('meta_description', $meta_description)->isModified()) {
			return $this;
		}
		$this->meta_description = $meta_description;

		return $this;
	}

	/**
	 * @name    getMetaKeywords ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getMetaKeywords() {
		return $this->meta_keywords;
	}

	/**
	 * @name    setMetaKeywords ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param mixed $meta_keywords
	 *
	 * @return $this
	 */
	public function setMetaKeywords($meta_keywords) {
		if (!$this->setModified('meta_keywords', $meta_keywords)->isModified()) {
			return $this;
		}
		$this->meta_keywords = $meta_keywords;

		return $this;
	}

	/**
	 * @name    getMetaTilte ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getMetaTilte() {
		return $this->meta_tilte;
	}

	/**
	 * @name    setMetaTilte ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param mixed $meta_tilte
	 *
	 * @return $this
	 */
	public function setMetaTilte($meta_tilte) {
		if (!$this->setModified('meta_tilte', $meta_tilte)->isModified()) {
			return $this;
		}
		$this->meta_tilte = $meta_tilte;

		return $this;
	}

	/**
	 * @name    getPage ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @name    setPage ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param mixed $page
	 *
	 * @return $this
	 */
	public function setPage($page) {
		if (!$this->setModified('page', $page)->isModified()) {
			return $this;
		}
		$this->page = $page;

		return $this;
	}

	/**
	 * @name    getRevisionNumber ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getRevisionNumber() {
		return $this->revision_number;
	}

	/**
	 * @name    setRevisionNumber ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param 	mixed $revision_number
	 *
	 * @return $this
	 */
	public function setRevisionNumber($revision_number) {
		if (!$this->setModified('revision_number', $revision_number)->isModified()) {
			return $this;
		}
		$this->revision_number = $revision_number;

		return $this;
	}

	/**
	 * @name    getTitle ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @name    setTitle ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param 	mixed $title
	 *
	 * @return 	$this
	 */
	public function setTitle($title) {
		if (!$this->setModified('title', $title)->isModified()) {
			return $this;
		}
		$this->title = $title;

		return $this;
	}

	/**
	 * @name    getUrlKey ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  mixed
	 */
	public function getUrlKey() {
		return $this->url_key;
	}

	/**
	 * @name    setUrlKey ()
	 *
	 * @author  Can Berkol
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param mixed $url_key
	 *
	 * @return $this
	 */
	public function setUrlKey($url_key) {
		if (!$this->setModified('url_key', $url_key)->isModified()) {
			return $this;
		}
		$this->url_key = $url_key;

		return $this;
	}

	/**
	 * @name        getStatus ()
	 *
	 * @author      Can Berkol
	 *
	 * @since       1.0.1
	 * @version     1.0.1
	 *
	 * @return      mixed
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @name        setStatus ()
	 *
	 * @author      Can Berkol
	 *
	 * @since       1.0.1
	 * @version     1.0.1
	 *
	 * @param       mixed $status
	 *
	 * @return      $this
	 */
	public function setStatus($status) {
		if (!$this->setModified('status', $status)->isModified()) {
			return $this;
		}
		$this->status = $status;

		return $this;
	}

}
/**
 * Change Log:
 * **************************************
 * v1.0.1                      16.06.2015
 * Can Berkol
 * **************************************
 * FR :: status property added.
 *
 * **************************************
 * v1.0.0                      24.04.2015
 * TW #3568843
 * Can Berkol
 * **************************************
 * A getLanguage()
 * A getPage()
 * A getTitle()
 * A getUrlKey()
 * A getContent()
 * A getMetaDescription()
 * A getMetaKeywords()
 * A getMetaTitle()
 * A getRevisionNumber()
 * A setLanguage()
 * A setPage()
 * A setTitle()
 * A setUrlKey()
 * A setContent()
 * A setMetaDescription()
 * A setMetaKeywords()
 * A setMetaTitle()
 * A setRevisionNumber()
 *
 */