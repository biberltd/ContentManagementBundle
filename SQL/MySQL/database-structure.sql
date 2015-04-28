/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : bod_core

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2015-04-27 15:45:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for files_of_page
-- ----------------------------
DROP TABLE IF EXISTS `files_of_page`;
CREATE TABLE `files_of_page` (
  `file` int(10) unsigned NOT NULL COMMENT 'File associated with page.',
  `page` int(10) unsigned NOT NULL COMMENT 'Page where file is associated at.',
  `date_added` datetime NOT NULL COMMENT 'Time when file is associated with page.',
  `count_view` int(10) NOT NULL DEFAULT '0' COMMENT 'Count of file views.',
  `language` int(5) unsigned DEFAULT NULL COMMENT 'Language that file be shown in.',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'Custom sort order.',
  `date_updated` datetime NOT NULL COMMENT 'Date when the entry is last updated.',
  `date_removed` datetime DEFAULT NULL COMMENT 'Date when the entry is marked as removed.',
  UNIQUE KEY `idxUFileOfPage` (`file`,`page`) USING BTREE,
  KEY `idxFPageOfFile` (`page`) USING BTREE,
  KEY `idxFLanguageOfFile` (`language`) USING BTREE,
  KEY `idxNFilesOfPageDateAdded` (`date_added`) USING BTREE,
  KEY `idxNFilesOfPageDateUpdated` (`date_updated`),
  KEY `idxNFilesOfPageDateRemoved` (`date_removed`),
  CONSTRAINT `idxFFileOfPage` FOREIGN KEY (`file`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFLanguageOfFile` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFPageOfFile` FOREIGN KEY (`page`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for layout
-- ----------------------------
DROP TABLE IF EXISTS `layout`;
CREATE TABLE `layout` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `code` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Code of layout. Cannot be modified.',
  `html` text COLLATE utf8_turkish_ci COMMENT 'Template code of layout.',
  `theme` int(10) unsigned NOT NULL COMMENT 'Theme folder of the layout.',
  `site` int(10) unsigned DEFAULT NULL COMMENT 'Site that layoug belongs to.',
  `bundle_name` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxULayoutId` (`id`) USING BTREE,
  UNIQUE KEY `idxULayoutCode` (`code`) USING BTREE,
  KEY `idxFThemeOfLayout` (`theme`) USING BTREE,
  KEY `idxFSiteOfLayout` (`site`) USING BTREE,
  CONSTRAINT `idxFSiteOfLayout` FOREIGN KEY (`site`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFThemeOfLayout` FOREIGN KEY (`theme`) REFERENCES `theme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for layout_localization
-- ----------------------------
DROP TABLE IF EXISTS `layout_localization`;
CREATE TABLE `layout_localization` (
  `layout` int(10) unsigned NOT NULL COMMENT 'Localized layout.',
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized layout name.',
  `url_key` varchar(55) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized URL key.',
  PRIMARY KEY (`layout`,`language`),
  UNIQUE KEY `idxULayoutLocalization` (`language`,`layout`) USING BTREE,
  UNIQUE KEY `idxULayoutUrlKey` (`language`,`layout`,`url_key`) USING BTREE,
  KEY `idxFLocalizedLayout` (`layout`) USING BTREE,
  KEY `idxFLayoutLocalizationLanguage` (`language`) USING BTREE,
  CONSTRAINT `idxFLayoutLocalizationLanguage` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFLocalizedLayout` FOREIGN KEY (`layout`) REFERENCES `layout` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for module
-- ----------------------------
DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `code` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'System access code. cannot be modified.',
  `html` text COLLATE utf8_turkish_ci COMMENT 'HTML code including template variables.',
  `theme` int(10) unsigned NOT NULL COMMENT 'Theme folder of the module.',
  `site` int(10) unsigned DEFAULT NULL COMMENT 'Site of module.',
  `bundle_name` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUModuleId` (`id`) USING BTREE,
  UNIQUE KEY `idxUModuleCode` (`code`) USING BTREE,
  KEY `idxFSiteOfModule` (`site`) USING BTREE,
  KEY `idxFThemeOfModule` (`theme`) USING BTREE,
  CONSTRAINT `idx_f_module_site` FOREIGN KEY (`site`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_f_module_theme` FOREIGN KEY (`theme`) REFERENCES `theme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for module_localization
-- ----------------------------
DROP TABLE IF EXISTS `module_localization`;
CREATE TABLE `module_localization` (
  `module` int(10) unsigned NOT NULL COMMENT 'Localized module.',
  `language` int(5) unsigned NOT NULL COMMENT 'Language of localization.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized name.',
  `url_key` varchar(55) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized URL key.',
  UNIQUE KEY `idxUModuleLocalization` (`language`,`module`) USING BTREE,
  UNIQUE KEY `idxUModuleUrlKey` (`url_key`,`language`,`module`) USING BTREE,
  KEY `UdxFLanguageOfModuleLocalization` (`language`) USING BTREE,
  KEY `idxFModuleOfModuleLocalization` (`module`) USING BTREE,
  CONSTRAINT `idxFLanguageOfModuleLocalization` FOREIGN KEY (`language`) REFERENCES `language` (`id`),
  CONSTRAINT `idxFModuleOfModuleLocalization` FOREIGN KEY (`module`) REFERENCES `module` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for modules_of_layout
-- ----------------------------
DROP TABLE IF EXISTS `modules_of_layout`;
CREATE TABLE `modules_of_layout` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `layout` int(10) unsigned NOT NULL COMMENT 'Module layout.',
  `module` int(10) unsigned NOT NULL COMMENT 'Layout module.',
  `section` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Section of layout. <!-- section:example --> <!-- section:example --/>',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  `page` int(10) unsigned NOT NULL COMMENT 'Page where module is located.',
  `style` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Extra style guide / trigger if needed.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUModulesOfLayoutId` (`id`) USING BTREE,
  UNIQUE KEY `idxUModulesOfLayout` (`id`,`layout`,`module`,`section`),
  KEY `idxFLayoutOfModule` (`layout`) USING BTREE,
  KEY `idxFModuleOfLayout` (`module`) USING BTREE,
  KEY `idxFPageOfModule` (`page`) USING BTREE,
  KEY `idxNSectionOfLayout` (`section`) USING BTREE,
  CONSTRAINT `idxFLayoutOfModule` FOREIGN KEY (`layout`) REFERENCES `layout` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFModuleOfLayout` FOREIGN KEY (`module`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFPageOfModule` FOREIGN KEY (`page`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for navigation
-- ----------------------------
DROP TABLE IF EXISTS `navigation`;
CREATE TABLE `navigation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `code` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'System code. Cannot be modified.',
  `site` int(10) unsigned DEFAULT NULL COMMENT 'Site that owns the navigation.',
  `date_added` datetime NOT NULL COMMENT 'Date when the navigation is added.',
  `date_updated` datetime NOT NULL COMMENT 'Date when the entry is last updated.',
  `date_removed` datetime DEFAULT NULL COMMENT 'Date when the entry is amrked as removed.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUNaviagationId` (`id`) USING BTREE,
  UNIQUE KEY `idxUNaviagationCode` (`code`) USING BTREE,
  KEY `idxFSiteOfNavigation` (`site`) USING BTREE,
  KEY `idxNNavigationDateAdded` (`date_added`),
  KEY `idxNNavigationDateUpdated` (`date_updated`),
  KEY `idxNNavigationDateRemoved` (`date_removed`),
  CONSTRAINT `idxFSiteOfNavigation` FOREIGN KEY (`site`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for navigation_item
-- ----------------------------
DROP TABLE IF EXISTS `navigation_item`;
CREATE TABLE `navigation_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `url` text COLLATE utf8_turkish_ci NOT NULL COMMENT 'URL address of the item target.',
  `target` varchar(1) COLLATE utf8_turkish_ci NOT NULL COMMENT 's:self, b:blank',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Sort order of the navigation item.',
  `navigation` int(10) unsigned NOT NULL,
  `page` int(10) unsigned DEFAULT NULL,
  `parent` int(10) unsigned DEFAULT NULL,
  `is_child` varchar(1) COLLATE utf8_turkish_ci NOT NULL COMMENT 'n:no, y:yes',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUNavigationItemId` (`id`) USING BTREE,
  KEY `idxFNavigationOfNavigationItem` (`navigation`) USING BTREE,
  KEY `idxFParentNavigationItem` (`parent`) USING BTREE,
  KEY `idxUPageOfNavigationItem` (`page`) USING BTREE,
  CONSTRAINT `idx_f_navigation_item_navigation` FOREIGN KEY (`navigation`) REFERENCES `navigation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idx_f_navigation_item_page` FOREIGN KEY (`page`) REFERENCES `page` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `idx_f_navigation__item_parent` FOREIGN KEY (`parent`) REFERENCES `navigation_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for navigation_item_localization
-- ----------------------------
DROP TABLE IF EXISTS `navigation_item_localization`;
CREATE TABLE `navigation_item_localization` (
  `language` int(5) unsigned NOT NULL COMMENT 'Navigation language.',
  `navigation_item` int(10) unsigned NOT NULL COMMENT 'Localized navigation item.',
  `title` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized title of item.',
  `url_key` varchar(55) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized URL key.',
  `description` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized description.',
  PRIMARY KEY (`language`,`navigation_item`),
  UNIQUE KEY `idxUNavigationItemLocalization` (`language`,`navigation_item`) USING BTREE,
  UNIQUE KEY `idxUNavigationItemUrlKey` (`language`,`navigation_item`,`url_key`) USING BTREE,
  KEY `idxFNavigationItemLocalizationLanguage` (`language`) USING BTREE,
  KEY `idxFLocalizedNavigationItem` (`navigation_item`) USING BTREE,
  CONSTRAINT `idxFLocalizedNavigationItem` FOREIGN KEY (`navigation_item`) REFERENCES `navigation_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFNavigationItemLocalizationLanguage` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for navigation_localization
-- ----------------------------
DROP TABLE IF EXISTS `navigation_localization`;
CREATE TABLE `navigation_localization` (
  `language` int(10) unsigned NOT NULL COMMENT 'Localization language.',
  `navigation` int(10) unsigned NOT NULL COMMENT 'Localized navigation.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized name.',
  `url_key` varchar(55) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized URL key.',
  `description` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`language`,`navigation`),
  UNIQUE KEY `idxUNavigationLocalization` (`navigation`,`language`) USING BTREE,
  UNIQUE KEY `idxUNavigationUrlKey` (`language`,`navigation`,`url_key`) USING BTREE,
  KEY `idxFNavigationLocalizationLanguage` (`language`) USING BTREE,
  KEY `idxFLocalizedNavigation` (`navigation`) USING BTREE,
  CONSTRAINT `idxFLocalizedNavigation` FOREIGN KEY (`navigation`) REFERENCES `navigation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFNavigationLocalizationLanguage` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for page
-- ----------------------------
DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `code` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Unique code that is used by the system. Cannot be modified.',
  `status` varchar(1) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'e' COMMENT 'e:editable by user, s:editable by support technician',
  `layout` int(10) unsigned NOT NULL COMMENT 'Page layout.',
  `site` int(10) unsigned DEFAULT NULL COMMENT 'Site that page belongs to.',
  `bundle_name` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUPageId` (`id`) USING BTREE,
  UNIQUE KEY `idxUPageCode` (`code`) USING BTREE,
  KEY `idxFSiteOfPage` (`site`) USING BTREE,
  KEY `idxFLayoutOfPage` (`layout`) USING BTREE,
  CONSTRAINT `idxFLayoutOfPage` FOREIGN KEY (`layout`) REFERENCES `layout` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `idxFSiteOfPage` FOREIGN KEY (`site`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for page_localization
-- ----------------------------
DROP TABLE IF EXISTS `page_localization`;
CREATE TABLE `page_localization` (
  `page` int(10) unsigned NOT NULL COMMENT 'Localized page.',
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `title` varchar(155) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized page title.',
  `content` text COLLATE utf8_turkish_ci COMMENT 'Localized page content.',
  `url_key` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized page URL key.',
  `meta_title` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized page meta title.',
  `meta_description` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized page meta description.',
  `meta_keywords` text COLLATE utf8_turkish_ci COMMENT 'Localized page meta keywords.',
  PRIMARY KEY (`page`,`language`),
  UNIQUE KEY `idxUPageLocalization` (`language`,`page`) USING BTREE,
  UNIQUE KEY `idxUPageUrlKey` (`page`,`language`,`url_key`) USING BTREE,
  KEY `idxFLayoutLocalizationLanguage` (`language`) USING BTREE,
  KEY `idxFLocalizedPage` (`page`) USING BTREE,
  CONSTRAINT `idxFLocalizedPage` FOREIGN KEY (`page`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFPageLocalizationLanguage` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for page_revision
-- ----------------------------
DROP TABLE IF EXISTS `page_revision`;
CREATE TABLE `page_revision` (
  `page` int(10) unsigned NOT NULL COMMENT 'Localized page.',
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `title` varchar(155) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized page title.',
  `content` text COLLATE utf8_turkish_ci COMMENT 'Localized page content.',
  `url_key` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized page URL key.',
  `meta_title` varchar(155) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized page meta title.',
  `meta_description` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized page meta description.',
  `meta_keywords` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'Localized page meta keywords.',
  `revision_number` int(10) unsigned NOT NULL COMMENT 'Unix time stamp.',
  `date_added` datetime NOT NULL COMMENT 'Date when the entry is added.',
  `date_updated` datetime NOT NULL COMMENT 'Date when the entry is updated.',
  `date_removed` datetime DEFAULT NULL COMMENT 'Date when the entry is marked as removed.',
  PRIMARY KEY (`page`,`language`),
  UNIQUE KEY `idxUPageRevision` (`language`,`page`,`revision_number`) USING BTREE,
  KEY `idxFLanguageOfRevision` (`language`) USING BTREE,
  KEY `idxFPageOfRevision` (`page`) USING BTREE,
  CONSTRAINT `idxFLanguageOfRevision` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFPageOfRevision` FOREIGN KEY (`page`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for theme
-- ----------------------------
DROP TABLE IF EXISTS `theme`;
CREATE TABLE `theme` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System given id.',
  `folder` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL,
  `type` varchar(1) COLLATE utf8_turkish_ci DEFAULT 'f' COMMENT 'f:front end, c:control panel',
  `date_added` datetime DEFAULT NULL COMMENT 'Date when the theme is added.',
  `date_updated` datetime DEFAULT NULL,
  `count_modules` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Module count.',
  `count_layouts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Layout counts.',
  `site` int(10) unsigned DEFAULT NULL COMMENT 'Site that theme belongs to.',
  `date_removed` datetime DEFAULT NULL COMMENT 'Date when the theme is marked as removed.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxUThemeId` (`id`) USING BTREE,
  UNIQUE KEY `idxUThemeFolder` (`folder`) USING BTREE,
  KEY `idxFSiteOfTheme` (`site`) USING BTREE,
  KEY `idxNDateAdded` (`date_added`) USING BTREE,
  KEY `idxNDateUpdated` (`date_updated`) USING BTREE,
  KEY `idxNDateRemoved` (`date_removed`),
  CONSTRAINT `idxFSiteOfTheme` FOREIGN KEY (`site`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for theme_localization
-- ----------------------------
DROP TABLE IF EXISTS `theme_localization`;
CREATE TABLE `theme_localization` (
  `theme` int(10) unsigned NOT NULL COMMENT 'Localized theme.',
  `language` int(5) unsigned NOT NULL COMMENT 'Localization language.',
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Localized name.',
  UNIQUE KEY `idxUThemeLocalization` (`name`,`language`) USING BTREE,
  KEY `idxFThemeLocalizationLanguage` (`language`) USING BTREE,
  KEY `idxFLocalizedTheme` (`theme`) USING BTREE,
  CONSTRAINT `idxFLocalizedTheme` FOREIGN KEY (`theme`) REFERENCES `theme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idxFThemeLocalizationLanguage` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;
