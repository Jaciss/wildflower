<?php
//include 'plugins/acl_app/acl_app_controller.php';
class AclController extends AclAppController {
	var $name = 'Acl';

	var $uses = array('Acl.AclAco', 'Acl.AclAro');

	var $helpers = array('Html', 'Javascript');

	function admin_index() {

	}

	function admin_aros() {

	}

	function admin_acos() {

	}

	function admin_permissions() {

	}

	function admin_buildAro(){
		$aro =& $this->Acl->Aro;
		$root = $aro->node('groups');
        if (!$root) {
            $aro->create(array('parent_id' => null, 'model' => null, 'alias' => 'groups'));
            $root = $aro->save();
            $root['Aro']['id'] = $aro->id; 
            $log[] = 'Created Aro node for groups';
        } else {
            $root = $root[0];
        }
		App::import('class','WildGroup',true,array('/var/www/projects/raf/wildflower/models'));
		$wg = new WildGroup();
		$groups = $wg->findAll();
		
		foreach($groups as $group){
			$group_name = $group['WildGroup']['name'];
			$group_members = $group['WildUser'];
			$groupNode = $aro->node('groups/'.$group_name);
			if (!$groupNode) {
				$aro->create(array('parent_id' => $root['Aro']['id'], 'foreign_key'=>$group['WildGroup']['id'], 'model' => 'WildGroup', 'alias' => $group_name));
				$groupNode = $aro->save();
				$log[] = 'Created Aro node for '. $group_name ."(".$aro->id.")";
				$groupNode['Aro']['id'] = $aro->id;
			}
			foreach($group_members as $member){
				$member_name = $member['login'];
				$member_node = $aro->node('groups/'.$group_name.'/'.$member_name);
				if(!$member_node){
					$aro->create(array('parent_id' => $groupNode['Aro']['id'], 'foreign_key'=>$member['id'], 'model' => 'WildUser', 'alias' => $member_name));
					$memberNode = $aro->save();
					$log[] = 'Created Aro node for '. $member_name.'('.$aro->id.')';
				}
			}
		}
		print_r($log);
	}

/**
 * Rebuild the Acl based on the current controllers in the application
 *
 * @return void
 */
    function admin_buildAco() {
        $log = array();
 
        $aco =& $this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
            $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id; 
            $log[] = 'Created Aco node for controllers';
        } else {
            $root = $root[0];
        }   
 
        App::import('Core', 'File');
        $Controllers = Configure::listObjects('controller');
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false ) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'buildAcl';
 
		$Plugins = $this->_get_plugin_controller_names();
        $Controllers = array_merge($Controllers, $Plugins);

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            App::import('Controller', $ctrlName);
            $ctrlclass = $ctrlName . 'Controller';
            $methods = get_class_methods($ctrlclass);
 
            // find / make controller node
            $controllerNode = $aco->node('controllers/'.$ctrlName);
            if (!$controllerNode) {
                $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
                $controllerNode = $aco->save();
                $controllerNode['Aco']['id'] = $aco->id;
                $log[] = 'Created Aco node for '.$ctrlName;
            } else {
                $controllerNode = $controllerNode[0];
            }
 
            //clean the methods. to remove those in Controller and private actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
                if (!$methodNode) {
                    $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                    $methodNode = $aco->save();
                    $log[] = 'Created Aco node for '. $method;
                }
            }
        }
        debug($log);
    }

	/**
     * Get the names of the plugin controllers ...
     * 
     * This function will get an array of the plugin controller names, and
     * also makes sure the controllers are available for us to get the 
     * method names by doing an App::import for each plugin controller.
     *
     * @return array of plugin names.
     *
     */
    function _get_plugin_controller_names(){
        App::import('Core', 'File', 'Folder');
        $paths = Configure::getInstance();
        $folder =& new Folder();
        // Change directory to the plugins
        $folder->cd(APP.'plugins');
        // Get a list of the files that have a file name that ends
        // with controller.php
        $files = $folder->findRecursive('.*_controller\.php');
        // Get the list of plugins
        $Plugins = Configure::listObjects('plugin');

        // Loop through the controllers we found int the plugins directory
        foreach($files as $f => $fileName)
        {
            // Get the base file name
            $file = basename($fileName);

            // Get the controller name
            $file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));

            // Loop through the plugins
            foreach($Plugins as $pluginName){
                if (preg_match('/^'.$pluginName.'/', $file)){
                    // First get rid of the App controller for the plugin
                    // We do this because the app controller is never called
                    // directly ...
                    if (preg_match('/^'.$pluginName.'App/', $file)){
                        unset($files[$f]);
                    } else {
                                    if (!App::import('Controller', $pluginName.'.'.$file))
                                    {
                                        debug('Error importing '.$file.' for plugin '.$pluginName);
                                    }

                        /// Now prepend the Plugin name ...
                        // This is required to allow us to fetch the method names.
                        $files[$f] = $file;
                    }
                    break;
                }
            }
        }

        return $files;
    }

}

?>