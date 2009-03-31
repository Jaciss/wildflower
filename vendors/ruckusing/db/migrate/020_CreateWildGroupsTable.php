<?php 
class CreateWildGroupsTable extends Ruckusing_BaseMigration {

	function up() {
        $groups = $this->create_table('wild_groups');
        $groups->column('name', 'text');
		$groups->column('created', 'datetime');
		$groups->column('modified', 'datetime');
        $groups->finish();
		
		$this->add_column('wild_users', 'wild_group_id', 'integer');
		
		$this->execute("INSERT INTO `wild_groups` (`id`, `name`, `created`, `modified`) VALUES
						(1, 'dev', '2009-03-19 15:39:50', '2009-03-19 15:39:50'),
						(2, 'admin', '2009-03-19 15:44:30', '2009-03-19 15:44:30'),
						(3, 'registered', '2009-03-19 15:44:50', '2009-03-19 15:44:50'),
						(4, 'member', '2009-03-19 15:45:02', '2009-03-19 15:45:02'),
						(5, 'anonymous', '2009-03-19 15:45:15', '2009-03-19 15:45:15');
						");
		
		$this->execute("UPDATE wild_users SET wild_group_id = '2' WHERE login LIKE 'admin'");
		$this->execute("UPDATE wild_users SET wild_group_id = '4' WHERE login LIKE 'tester'");
	}

	function down() {
        $this->drop_table('wild_groups');
		$this->remove_column('wild_users', 'wild_group_id');
	}
}