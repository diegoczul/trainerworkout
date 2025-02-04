<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class System extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:confirmationEmails';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remind Users about Confirmation';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()	
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$controller = new SystemController();
		$controller->dailyActivity();	
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
