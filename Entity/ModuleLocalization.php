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
 *     name="module_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUModuleLocalization", columns={"module","language"}),
 *         @ORM\UniqueConstraint(name="idxUModuleUrlKey", columns={"module","language","url_key"})
 *     }
 * )
 */
class ModuleLocalization extends CoreEntity
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
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language
     */
    private $language;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Module", inversedBy="localizations")
     * @ORM\JoinColumn(name="module", referencedColumnName="id", nullable=false)
     * @var \BiberLtd\Bundle\ContentManagementBundle\Entity\Module
     */
    private $module;

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
     * @param string $name
     *
     * @return $this
     */
    public function setName(\string $name) {
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