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
use BiberLtd\Bundle\CoreBundle\CoreLocalizableEntity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreEntity;
/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="page_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUPageLocalization", columns={"page","language"}),
 *         @ORM\UniqueConstraint(name="idxUPageUrlKey", columns={"page","language","url_key"})
 *     }
 * )
 */
class PageLocalization extends CoreEntity
{
    /** 
     * @ORM\Column(type="string", length=155, nullable=false)
     * @var string
     */
    private $title;

    /** 
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string
     */
    private $url_key;

    /** 
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $content;

    /** 
     * @ORM\Column(type="string", length=155, nullable=true)
     * @var string
     */
    private $meta_title;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $meta_description;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $meta_keywords;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language
     */
    private $language;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Page", inversedBy="localizations")
     * @ORM\JoinColumn(name="page", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Page
     */
    private $page;

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(\string $content) {
        if(!$this->setModified('content', $content)->isModified()) {
            return $this;
        }
        $this->content = $content;
		return $this;
    }

    /**
     * @return string
     */
    public function getContent() {
        return $this->content;
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
     * @param string $meta_description
     *
     * @return $this
     */
    public function setMetaDescription(\string $meta_description) {
        if(!$this->setModified('meta_description', $meta_description)->isModified()) {
            return $this;
        }
        $this->meta_description = $meta_description;
		return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription() {
        return $this->meta_description;
    }

    /**
     * @param string $meta_keywords
     *
     * @return $this
     */
    public function setMetaKeywords(\string $meta_keywords) {
        if(!$this->setModified('meta_keywords', $meta_keywords)->isModified()) {
            return $this;
        }
        $this->meta_keywords = $meta_keywords;
		return $this;
    }

    /**
     * @return string
     */
    public function getMetaKeywords() {
        return $this->meta_keywords;
    }

    /**
     * @param string $meta_title
     *
     * @return $this
     */
    public function setMetaTitle(\string $meta_title) {
        if(!$this->setModified('meta_title', $meta_title)->isModified()) {
            return $this;
        }
        $this->meta_title = $meta_title;
		return $this;
    }

    /**
     * @return string
     */
    public function getMetaTitle() {
        return $this->meta_title;
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
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(\string $title) {
        if(!$this->setModified('title', $title)->isModified()) {
            return $this;
        }
        $this->title = $title;
		return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $url_key
     *
     * @return $this
     */
    public function setUrlKey(\string $url_key) {
        if(!$this->setModified('url_key', $url_key)->isModified()) {
            return $this;
        }
        $this->url_key = $url_key;
		return $this;
    }

    /**
     * @return string
     */
    public function getUrlKey() {
        return $this->url_key;
    }
}