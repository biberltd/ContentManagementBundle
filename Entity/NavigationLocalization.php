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
 *     name="navigation_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUNavigationLocalization", columns={"navigation","language"}),
 *         @ORM\UniqueConstraint(name="idxUNavigationUrlKey", columns={"navigation","language","url_key"})
 *     }
 * )
 */
class NavigationLocalization extends CoreEntity
{
    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     * @var string
     */
    private $name;

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
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation",
     *     inversedBy="localizations"
     * )
     * @ORM\JoinColumn(name="navigation", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation
     */
    private $navigation;

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description) {
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
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name) {
        if(!$this->setModified('name', $name)->isModified()) {
            return $this;
        }
        $this->name = $name;
		return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param \BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation $navigation
     *
     * @return $this
     */
    public function setNavigation(\BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation $navigation) {
        if(!$this->setModified('navigation', $navigation)->isModified()) {
            return $this;
        }
        $this->navigation = $navigation;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\ContentManagementBundle\Entity\Navigation
     */
    public function getNavigation() {
        return $this->navigation;
    }

    /**
     * @param string $url_key
     *
     * @return $this
     */
    public function setUrlKey(string $url_key) {
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