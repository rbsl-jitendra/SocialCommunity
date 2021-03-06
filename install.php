<?php
/**
 * @package      SocialCommunity
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * SocialCommunity is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined( '_JEXEC' ) or die;

/**
 * Install script file of the component
 */
class pkg_socialCommunityInstallerScript {
    
        /**
         * method to install the component
         *
         * @return void
         */
        public function install($parent) {
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
        public function uninstall($parent) {
        }
 
        /**
         * method to update the component
         *
         * @return void
         */
        public function update($parent) {
        }
 
        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         */
        public function preflight($type, $parent) {
        }
 
        /**
         * method to run after an install/update/uninstall method
         *
         * @return void
         */
        public function postflight($type, $parent) {

            if(strcmp($type, "install") == 0) {
                
                if(!defined("SOCIALCOMMUNITY_PATH_COMPONENT_ADMINISTRATOR")) {
                    define("SOCIALCOMMUNITY_PATH_COMPONENT_ADMINISTRATOR", JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR ."com_socialcommunity");
                }
                
                // Register Install Helper
                JLoader::register("SocialCommunityInstallHelper", SOCIALCOMMUNITY_PATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . "helpers" . DIRECTORY_SEPARATOR ."install.php");
                
                jimport('joomla.filesystem.path');
                jimport('joomla.filesystem.folder');
                jimport('joomla.filesystem.file');
                
                $params             = JComponentHelper::getParams("com_socialcommunity");
                $this->imagesFolder = JFolder::makeSafe($params->get("images_directory", "images/profiles"));
                $this->imagesPath   = JPath::clean( JPATH_SITE.DIRECTORY_SEPARATOR.$this->imagesFolder );
                $this->bootstrap    = JPath::clean( JPATH_SITE.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR."com_socialcommunity".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR. "admin".DIRECTORY_SEPARATOR."bootstrap.min.css" );
                
                $style = '<style>'.file_get_contents($this->bootstrap).'</style>';
                echo $style;
                
                // Create images folder
                if(!is_dir($this->imagesPath)){
                    SocialCommunityInstallHelper::createFolder($this->imagesPath);
                }
                
                // Start table with the information
                SocialCommunityInstallHelper::startTable();
                
                // Requirements
                SocialCommunityInstallHelper::addRowHeading(JText::_("COM_SOCIALCOMMUNITY_MINIMUM_REQUIREMENTS"));
                
                // Display result about verification for existing folder
                $title  = JText::_("COM_SOCIALCOMMUNITY_IMAGE_FOLDER");
                $info   = $this->imagesFolder;
                if(!is_dir($this->imagesPath)) {
                    $result = array("type" => "important", "text" => JText::_("JON"));
                } else {
                    $result = array("type" => "success"  , "text" => JText::_("JYES"));
                }
                SocialCommunityInstallHelper::addRow($title, $result, $info);
                
                // Display result about verification for writeable folder
                $title  = JText::_("COM_SOCIALCOMMUNITY_WRITABLE_FOLDER");
                $info   = $this->imagesFolder;
                if(!is_writable($this->imagesPath)) {
                    $result = array("type" => "important", "text" => JText::_("JON"));
                } else {
                    $result = array("type" => "success"  , "text" => JText::_("JYES"));
                }
                SocialCommunityInstallHelper::addRow($title, $result, $info);
                
                // Display result about verification for GD library
                $title  = JText::_("COM_SOCIALCOMMUNITY_GD_LIBRARY");
                $info   = "";
                if(!extension_loaded('gd') AND function_exists('gd_info')) {
                    $result = array("type" => "important", "text" => JText::_("COM_SOCIALCOMMUNITY_WARNING"));
                } else {
                    $result = array("type" => "success"  , "text" => JText::_("JON"));
                }
                SocialCommunityInstallHelper::addRow($title, $result, $info);
                
                // Display result about verification for cURL library
                $title  = JText::_("COM_SOCIALCOMMUNITY_CURL_LIBRARY");
                $info   = "";
                if( !extension_loaded('curl') ) {
                    $info   = JText::_("COM_SOCIALCOMMUNITY_CURL_INFO");
                    $result = array("type" => "important", "text" => JText::_("COM_SOCIALCOMMUNITY_WARNING"));
                } else {
                    $result = array("type" => "success"  , "text" => JText::_("JON"));
                }
                SocialCommunityInstallHelper::addRow($title, $result, $info);
                
                // Display result about verification Magic Quotes
                $title  = JText::_("COM_SOCIALCOMMUNITY_MAGIC_QUOTES");
                $info   = "";
                if( get_magic_quotes_gpc() ) {
                    $info   = JText::_("COM_SOCIALCOMMUNITY_MAGIC_QUOTES_INFO");
                    $result = array("type" => "important", "text" => JText::_("JON"));
                } else {
                    $result = array("type" => "success"  , "text" => JText::_("JOFF"));
                }
                SocialCommunityInstallHelper::addRow($title, $result, $info);
                
                // Installed extensions
                
                SocialCommunityInstallHelper::addRowHeading(JText::_("COM_SOCIALCOMMUNITY_INSTALLED_EXTENSIONS"));
                
                // SocialCommunity Library
                $result = array("type" => "success"  , "text" => JText::_("COM_SOCIALCOMMUNITY_INSTALLED"));
                SocialCommunityInstallHelper::addRow(JText::_("COM_SOCIALCOMMUNITY_SOCIALCOMMUNITY_LIBRARY"), $result, JText::_("COM_SOCIALCOMMUNITY_LIBRARY"));
                
                // User - Social Community New User
                $result = array("type" => "success"  , "text" => JText::_("COM_SOCIALCOMMUNITY_INSTALLED"));
                SocialCommunityInstallHelper::addRow(JText::_("COM_SOCIALCOMMUNITY_USER_SOCIALCOMMUNITY_NEW_USER"), $result, JText::_("COM_SOCIALCOMMUNITY_PLUGIN"));
                
                // End table with the information
                SocialCommunityInstallHelper::endTable();
                
            }
            
            echo JText::sprintf("COM_SOCIALCOMMUNITY_MESSAGE_REVIEW_SAVE_SETTINGS", JRoute::_("index.php?option=com_socialcommunity"));
            
            
        }
}
