<?php
/***********************************************************
* Project  :
* Name     : Utils
* Modified : $Id: utils.inc.php,v 3c1087e5854c 2011/06/23 22:24:30 ForJest $
* Author   : forjest@gmail.com
************************************************************
*
*
*
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class TreeUtils
{

function __construct()
{
     $this-> Attrs = array(
     					'real_id',
     					'children',
     					'duplicate_id',
     					'parent_id',
     					'label',
     					'link',
     					'color',
     					'color-caption',
     					'color-tag',
     					'color-node',
     					'size',
     					'size-caption',
     					/*'size-node',*/
     					'type','img',
     					'wrapper',
     					'border-color',
     					'border-width',
     					'shape',
     					'adlink-text',
     					'adlink-url',
     					'left',
     					'right',
     					'pagecheck',
     					'usercheck'
     );
     $this-> js_new_line = '\\n\\'."\n";
}
///////////////////////////////////////////////////////////////////////////////


function get_only_allowed_opts($node)
{
     if (!empty($node['caption']))
     {
          $node['label'] = $node['caption'];
     }
     if(empty($node['duplicate_id']))
     {
     	  $node['duplicate_id'] = 'a';
     }
   //  $node['children'] = 30;
     $result = array();
     foreach ($this-> Attrs as $attr)
     {
          if (!empty($node[$attr]))
          {
              $result[$attr] = $node[$attr];
          }
     }
     if (isset($result['size']))
     {
          if (!isset($result['size-caption']))
          {
               $result['size-caption'] = $result['size'];
          }
          unset($result['size']);
     }
     return (object)$result;
}
////////////////////////////////////////////////////////////////////////////

function get_options_str($options)
{
     return str_replace('"', '', json_encode($this-> get_only_allowed_opts($options)));
}
////////////////////////////////////////////////////////////////////////////

function get_tree_str($Tree)
{
     $result = '';
     //print_r($Tree);echo '<br><br>';
     foreach ($Tree['nodes'] as $node)
     {
          $result .= $node['name'].' '.$this-> get_options_str($node).$this-> js_new_line;
          //echo '+++'.$node['name'].'+++';
     }
     foreach (@(array)$Tree['edges'] as $edge)
     {
          $result .= $edge['from'].'->'.$edge['to'].$this-> get_options_str($edge).$this-> js_new_line;
     }
     //print_r($result);
     return $result;
}
////////////////////////////////////////////////////////////////////////////
}//class ends here
?>