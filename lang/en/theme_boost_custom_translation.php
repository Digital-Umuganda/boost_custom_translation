<?php
// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();                                                                                                
                                                                                                                                    
// A description shown in the admin theme selector.                                                                                 
$string['choosereadme'] = 'Theme photo is a child theme of Boost. It adds the ability to upload background photos.';                
// The name of our plugin.                                                                                                          
$string['pluginname'] = 'Ting';                                                                                                    
// We need to includetheme_boost_customtranslation a lang string for each block region.                                                                          
$string['region-side-pre'] = 'Right';
$string['translate'] = 'Translate';
$string['enablepopup'] = 'Enable popup translation';
$string['sellang'] = 'Select course language';