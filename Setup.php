<?php

namespace YogstationPermissions;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;


class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
    {
        $this->schemaManager()->createTable('yg_linked_account', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('user_id', 'int');
			$table->addColumn('account_type', 'varchar', 64);
			$table->addColumn('account_id', 'varchar', 255);
			$table->addPrimaryKey(['user_id', 'account_type']);
		});

		$this->schemaManager()->createTable('yg_linking_key', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('linking_key', 'varchar', 255);
			$table->addColumn('account_type', 'varchar', 64);
			$table->addColumn('account_id', 'varchar', 255);
			$table->addColumn('expires', 'int');
			$table->addPrimaryKey('linking_key');
		});
	}

	public function uninstallStep1()
	{
		$this->schemaManager()->dropTable('yg_linked_account');
		$this->schemaManager()->dropTable('yg_linking_key');
	}

}