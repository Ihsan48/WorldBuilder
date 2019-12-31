<?php

declare(strict_types=1);

namespace cosmicpe\buildertools\command;

use cosmicpe\buildertools\command\check\CommandCheck;
use cosmicpe\buildertools\command\utils\CommandException;
use cosmicpe\buildertools\Loader;
use pocketmine\command\Command as PocketMineCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

abstract class Command extends PocketMineCommand{

	/** @var Loader */
	private $plugin;

	/** @var CommandCheck[] */
	private $checks = [];

	public function __construct(Loader $plugin, string $name, string $description, array $aliases = []){
		parent::__construct($name);
		$this->plugin = $plugin;
		$this->setDescription($description);
		$this->setAliases($aliases);
	}

	public function getPlugin() : Loader{
		return $this->plugin;
	}

	public function addCheck(CommandCheck $check) : void{
		$this->checks[] = $check;
	}

	final public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
		if(!$this->testPermission($sender)){
			return;
		}

		try{
			foreach($this->checks as $check){
				$check->validate($sender);
			}
			$this->onExecute($sender, $commandLabel, $args);
		}catch(CommandException $e){
			$sender->sendMessage(TextFormat::RED . $e->getMessage());
		}
	}

	abstract public function onExecute(CommandSender $sender, string $label, array $args) : void;
}