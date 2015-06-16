<?php

/**
 * @name        NavigationItemLocalization
 * @package		BiberLtd\Bundle\CoreBundle\ContentManagementBundle
 *
 * @author		Can Berkol
 *
 * @version     1.2.0
 * @date        19.12.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreEntity;
/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="navigation_item_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUNavigationItemLocalization", columns={"navigation_item","language"}),
 *         @ORM\UniqueConstraint(name="idxUNavigationItemUrlKey", columns={"navigation_item","language","url_key"})
 *     }
 * )
 */
class NavigationItemLocalization extends CoreEntity
{
    /**
     * @ORM\Column(type="string", length=45, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=55, nullable=false)
     */
    private $url_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $language;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem",
     *     inversedBy="localizations"
     * )
     * @ORM\JoinColumn(name="navigation_item", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $navigation_item;

    /**
     * @name            setDescription ()
     *                  Sets the description property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @use             $this->setModified()
     *
     * @param           string                  $description
     *
     * @return          object                  $this
     */
    public function setDescription($description) {
        if(!$this->setModified('description', $description)->isModified()) {
            return $this;
        }
        $this->description = $description;
        return $this;
    }

    /**
     * @name            getDescription ()
     *                  Returns the value of description property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @return          string           $this->description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @name            setLanguage ()
     *                  Sets the language property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @use             $this->setModified()
     *
     * @param           BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language                   $language
     *
     * @return          object                  $this
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
     *                  Returns the value of language property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @return          BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language           $this->language
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @name            setNavigationItem()
     *                  Sets the navigation_item property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $navigation_item
     *
     * @return          BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem                $this
     */
    public function setNavigationItem($navigation_item) {
        if(!$this->setModified('navigation_item', $navigation_item)->isModified()) {
            return $this;
        }
        $this->navigation_item = $navigation_item;
        return $this;
    }

    /**
     * @name            getNavigationItem()
     *                  Returns the value of navigation_item property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem           $this->navigation_item
     */
    public function getNavigationItem() {
        return $this->navigation_item;
    }

    /**
     * @name            setTitle ()
     *                  Sets the title property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           string                  $title
     *
     * @return          object                  $this
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
     *                  Returns the value of title property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          string           $this->title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @name            setUrlKey ()
     *                  Sets the url_key property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.0
     *
     * @use             $this->setModified()
     *
     * @param           string                $url_key
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
     *                  Returns the value of url_key property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          string           $this->url_key
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
 * v1.2.0                      Can Berkol
 * 19.12.2013
 * **************************************
 * File resetted.
 */