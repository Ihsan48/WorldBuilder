<?php

declare(strict_types=1);

namespace cosmicpe\worldbuilder\event\player;

use cosmicpe\worldbuilder\editor\task\EditorTask;
use cosmicpe\worldbuilder\event\WorldBuilderEvent;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;

class PlayerTriggerEditorTaskEvent extends WorldBuilderEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		private Player $player,
		private EditorTask $task,
		private ?string $message = null
	){}

	public function getPlayer() : Player{
		return $this->player;
	}

	public function getTask() : EditorTask{
		return $this->task;
	}

	public function getMessage() : ?string{
		return $this->message;
	}

	public function setMessage(?string $message) : void{
		$this->message = $message;
	}
}