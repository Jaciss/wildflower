<h2 class="section">Access Control List Management</h2>
<ul class="acl">
  <li><?php print $html->image('/acl/img/tango/32x32/apps/system-users.png') ?><?php print $html->link('Manage Aros', array('action'=>'aros', 'admin'=>true)) ?></li>
  <li><?php print $html->image('/acl/img/tango/32x32/apps/preferences-system-windows.png') ?><?php print $html->link('Manage Acos', array('action'=>'acos', 'admin'=>true)) ?></li>
  <li><?php print $html->image('/acl/img/tango/32x32/emblems/emblem-readonly.png') ?><?php print $html->link('Manage Permissions', array('action'=>'permissions', 'admin'=>true)) ?></li>
</ul>
<br />

<h2>Quick Start</h2><br />

<div>
<b>ARO - Access Request Object</b><br />
Things (most often groups and users) that want to use stuff are called access request objects
<a href="acl/buildAro">Generate AROs</a>
</div><br />

<div>
<b>ACO - Access Control Object</b><br />
Things in the system that are wanted (most often controllers and actions or data) are called access control objects
<a href="acl/buildAco">Generate ACOs</a>
</div>

<h2>Known Bugs</h2>
<ul>
 <li>does not show inherited permissions</li>
 <li>does not show full path in finder</li>
 <li>does not have crud fields</li>
</ul>