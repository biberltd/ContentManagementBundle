<?php
/**
 * @name        PageLocalization
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
use BiberLtd\Core\CoreLocalizableEntity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Core\CoreEntity;
/** 
 * @ORM\Entity
 * @ORM\Table(
 *     name="page_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_page_localization", columns={"page","language"}),
 *         @ORM\UniqueConstraint(name="idx_u_page_url_key", columns={"page","language","url_key"})
 *     }
 * )
 */
class PageLocalization extends CoreEntity
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
    private $meta_title;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_description;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_keywords;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Core\Bundles\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $language;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Core\Bundles\ContentManagementBundle\Entity\Page",
     *     inversedBy="localizations"
     * )
     * @ORM\JoinColumn(name="page", referencedColumnName="id", onDelete="CASCADE")
     */
    private $page;

    /**
     * @name                  setContent ()
     *                                   Sets the content property.
     *                                   Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $content
     *
     * @return          object                $this
     */
    public function setContent($content) {
        if(!$this->setModified('content', $content)->isModified()) {
            return $this;
        }
        $this->content = $content;
		return $this;
    }

    /**
     * @name            getContent ()
     *                             Returns the value of content property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->content
     */
    public function getContent() {
        return $this->content;
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
     * @name                  setMetaDescription ()
     *                                           Sets the meta_description property.
     *                                           Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $meta_description
     *
     * @return          object                $this
     */
    public function setMetaDescription($meta_description) {
        if(!$this->setModified('meta_description', $meta_description)->isModified()) {
            return $this;
        }
        $this->meta_description = $meta_description;
		return $this;
    }

    /**
     * @name            getMetaDescription ()
     *                                     Returns the value of meta_description property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->meta_description
     */
    public function getMetaDescription() {
        return $this->meta_description;
    }

    /**
     * @name                  setMetaKeywords ()
     *                                        Sets the meta_keywords property.
     *                                        Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $meta_keywords
     *
     * @return          object                $this
     */
    public function setMetaKeywords($meta_keywords) {
        if(!$this->setModified('meta_keywords', $meta_keywords)->isModified()) {
            return $this;
        }
        $this->meta_keywords = $meta_keywords;
		return $this;
    }

    /**
     * @name            getMetaKeywords ()
     *                                  Returns the value of meta_keywords property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->meta_keywords
     */
    public function getMetaKeywords() {
        return $this->meta_keywords;
    }

    /**
     * @name                  setMetaTitle ()
     *                                     Sets the meta_title property.
     *                                     Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $meta_title
     *
     * @return          object                $this
     */
    public function setMetaTitle($meta_title) {
        if(!$this->setModified('meta_title', $meta_title)->isModified()) {
            return $this;
        }
        $this->meta_title = $meta_title;
		return $this;
    }

    /**
     * @name            getMetaTitle ()
     *                               Returns the value of meta_title property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->meta_title
     */
    public function getMetaTitle() {
        return $this->meta_title;
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
     * @name                  setTitle ()
     *                                 Sets the title property.
     *                                 Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $title
     *
     * @return          object                $this
     */
    public function setTitle($title) {
        if(!$this->setModified('title', $title)->isModified()) {
            return $this;
        }
        $this->title = $title;
		return $this;
    }

    /**
     * @name            getTitle ()
     *                           Returns the value of title property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @name                  setUrlKey ()
     *                                  Sets the url_key property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $url_key
     *
     * @return          object                $this
     */
    public function setUrlKey($url_key) {
        if(!$this->setModified('url_key', $url_key)->isModified()) {
            return $this;
        }
        $this->url_key = $url_key;
		return $this;
    }

    /**
     * @name            getUrlKey ()
     *                            Returns the value of url_key property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->url_key
     */
    public function getUrlKey() {
        return $this->url_key;
    }
    /******************************************************************
     * PUBLIC SET AND GET FUNCTIONS                                   *
     ******************************************************************/

}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Murat Ünal
 * 24.09.2013
 * **************************************
 * A getContent()
 * A getLanguage()
 * A getMetaDescription()
 * A getMetaKeywords()
 * A getMetaTitle()
 * A getPage()
 * A getTitle()
 * A getUrlKey()
 *
 * A setContent()
 * A setLanguage()
 * A setMetaDescription()
 * A setMetaKeywords()
 * A setMetaTitle()
 * A setPage()
 * A setTitle()
 * A setUrlKey()
 *
 */