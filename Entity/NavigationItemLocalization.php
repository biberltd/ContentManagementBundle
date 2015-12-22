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
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=55, nullable=false)
     * @var string
     */
    private $url_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language
     */
    private $language;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem",
     *     inversedBy="localizations"
     * )
     * @ORM\JoinColumn(name="navigation_item", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem
     */
    private $navigation_item;

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(\string $description) {
        if(!$this->setModified('description', $description)->isModified()) {
            return $this;
        }
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
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
     * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem $navigation_item
     *
     * @return $this
     */
    public function setNavigationItem(\BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem $navigation_item) {
        if(!$this->setModified('navigation_item', $navigation_item)->isModified()) {
            return $this;
        }
        $this->navigation_item = $navigation_item;
        return $this;
    }

    /**
     * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\NavigationItem
     */
    public function getNavigationItem() {
        return $this->navigation_item;
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