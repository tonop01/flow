<?php

namespace Kelemen\Flow\Action\Database\Mysql;

use Kelemen\Flow\Action\Action;
use Kelemen\Flow\Renderer\Renderer;
use Symfony\Component\Process\Process;

class CreateDatabaseMysql extends Action
{
	/** @var string */
	private $user;

	/** @var string */
	private $password;

	/** @var string */
	private $dbName;

	/**
	 * @param string $user
	 * @param string $password
	 * @param string $dbName
	 */
	public function __construct($user, $password, $dbName)
	{
		$this->user = $user;
		$this->password = $password;
		$this->dbName = $dbName;
	}

	/**
	 * @param Renderer $renderer
	 */
	public function run(Renderer $renderer)
	{
		$renderer->writeln($this, 'Creating MySQL database ' . $renderer->highlight($this->dbName));
		$process = Process::fromShellCommandline('mysql -u ' . $this->user  . ' -p' . $this->password . ' -e "CREATE DATABASE ' . $this->dbName . '"');
		$process->run();

		if ($process->isSuccessful()) {
			$renderer->writeSuccess($this, 'MySQL database ' . $renderer->highlight($this->dbName) . ' was created');
			return;
		}

		$renderer->writeError($this, 'MySQL database ' . $renderer->highlight($this->dbName) . ' was not created');
		$renderer->writeError($this, 'Reason: ' . $process->getErrorOutput());
	}
}
