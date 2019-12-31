<?php

declare(strict_types=1);

namespace cosmicpe\buildertools\command\defaults;

use cosmicpe\buildertools\command\check\RequireSelectionCheck;
use cosmicpe\buildertools\command\Command;
use cosmicpe\buildertools\editor\task\SetEditorTask;
use cosmicpe\buildertools\Loader;
use cosmicpe\buildertools\session\PlayerSessionManager;
use cosmicpe\buildertools\utils\BlockUtils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SetCommand extends Command{

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "/set", "Sets a given block in selected position");
		$this->addCheck(new RequireSelectionCheck());
	}

	public function onExecute(CommandSender $sender, string $label, array $args) : void{
		/** @var Player $sender */
		if(isset($args[0])){
			$block = BlockUtils::fromString($args[0]);
			if($block !== null){
				$session = PlayerSessionManager::get($sender);
				$session->pushEditorTask(new SetEditorTask($sender->getWorld(), $session->getSelection(), $block), TextFormat::GREEN . "Setting " . $block->getName());
				return;
			}
			$sender->sendMessage(TextFormat::RED . $args[0] . " is not a valid block.");
			return;
		}

		$sender->sendMessage(TextFormat::RED . "/" . $label . " <block>");
	}
}