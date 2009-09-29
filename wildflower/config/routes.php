<?php
/**
 * Wildflower routes
 *
 * Wildflower reservers these URL's:
 */

$wfControllers = array('pages', 'posts', 'dashboards', 'users', 'categories', 'comments', 'assets', 'messages', 'uploads', 'settings', 'utilities', 'widgets', 'sidebars', 'groups');
foreach ($wfControllers as $shortcut) {
	Router::connect(
		"/$prefix/$shortcut", 
		array('controller' => "wild_$shortcut", 'action' => 'index', 'prefix' => 'wf')
	);
	
	Router::connect(
		"/$prefix/$shortcut/:action/*", 
		array('controller' => "wild_$shortcut", 'prefix' => 'wf')
	);
}

foreach ($myWfAdminControllers as $controller) {
	Router::connect(
		"/$prefix/$controller", 
		array('controller' => $controller, 'action' => 'index', 'prefix' => 'wf')
	);
	
	Router::connect(
		"/$prefix/$controller/:action/*", 
		array('controller' => $controller, 'prefix' => 'wf')
	);
}

// Dashboard
Router::connect("/$prefix", array('controller' => 'wild_dashboards', 'action' => 'index', 'prefix' => 'wf'));
Router::connect("/$prefix/dashboards/search", array('controller' => 'wild_dashboards', 'action' => 'search', 'prefix' => 'wf'));

// Login screen
Router::connect("/$prefix/login", array('controller' => 'wild_users', 'action' => 'login'));

// Contact form
Router::connect('/contact', array('controller' => 'wild_messages', 'action' => 'index'));
Router::connect('/contact/create', array('controller' => 'wild_messages', 'action' => 'create'));

// RSS
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/feed', array('controller' => 'wild_posts', 'action' => 'feed'));

// Ultra sexy short SEO friendly post URLs in form of http://my-domain/p/40-char-uuid
Router::connect('/' . Configure::read('Wildflower.postsParent') . '/:slug', array('controller' => 'wild_posts', 'action' => 'view'));
Router::connect('/c/:slug', array('controller' => 'wild_posts', 'action' => 'category'));

// Home page
Router::connect('/', array('controller' => 'pages', 'action' => 'view'));
Router::connect('/app/webroot/', array('controller' => 'pages', 'action' => 'view'));

// Posts section
Router::connect('/rss', array('controller' => 'posts', 'action' => 'rss'));
Router::connect('/' . Configure::read('Wildflower.blogIndex'), array('controller' => 'posts', 'action' => 'index'));
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/*', array('controller' => 'posts', 'action' => 'index'));
Router::connect('/' . Configure::read('Wildflower.postsParent') . '/:slug', array('controller' => 'posts', 'action' => 'view'));
Router::connect('/c/:slug', array('controller' => 'posts', 'action' => 'category'));

// Wildflower admin routes
$prefix = Configure::read('Routing.admin');
Router::connect("/$prefix", array('controller' => 'dashboards', 'action' => 'index', 'admin' => true));

// Image thumbnails
// @TODO shorten to '/i/*'
Router::connect('/wildflower/thumbnail/*', array('controller' => 'assets', 'action' => 'thumbnail'));
Router::connect('/wildflower/thumbnail_by_id/*', array('controller' => 'assets', 'action' => 'thumbnail_by_id'));

// ACL
Router::connect("/$prefix/acl", array('controller' => 'acl', 'action' => 'acl', 'index', 'admin'=>true));
Router::connect("/$prefix/acl/*", array('controller' => 'acl', 'action' => 'acl', 'admin'=>true));

// Connect root pages slugs
App::import('Vendor', 'WfRootPagesCache', array('file' => 'WfRootPagesCache.php'));
WildflowerRootPagesCache::connect();


/**
 * Your routes here...
 */

