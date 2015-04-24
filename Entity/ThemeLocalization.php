<?php
/**
 * @name        ThemeLocalization
 * @package		BiberLtd\Bundle\CoreBundle\ContentManagementBundle
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
namespace BiberLtd\Bundle\ContentManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreEntity;
/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="theme_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idxUThemeLocalization", columns={"theme","language"})}
 * )
 */
class ThemeLocalization extends CoreEntity
{
    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     */
    private $name;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $language;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\ContentManagementBundle\Entity\Theme",
     *     inversedBy="localizations"
     * )
     * @ORM\JoinColumn(name="theme", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $theme;

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
     * @name                  setName ()
     *                                Sets the name property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $name
     *
     * @return          object                $this
     */
    public function setName($name) {
        if(!$this->setModified('name', $name)->isModified()) {
            return $this;
        }
        $this->name = $name;
		return $this;
    }

    /**
     * @name            getName ()
     *                          Returns the value of name property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @name                  setTheme ()
     *                                 Sets the theme property.
     *                                 Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $theme
     *
     * @return          object                $this
     */
    public function setTheme($theme) {
        if(!$this->setModified('theme', $theme)->isModified()) {
            return $this;
        }
        $this->theme = $theme;
		return $this;
    }

    /**
     * @name            getTheme ()
     *                           Returns the value of theme property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->theme
     */
    public function getTheme() {
        return $this->theme;
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
 * A getLanguage()
 * A getName()
 * A getTheme()
 *
 * A setLanguage()
 * A setName()
 * A setTheme()
 *
 */