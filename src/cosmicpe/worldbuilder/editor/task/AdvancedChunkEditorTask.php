<?php

declare(strict_types=1);

namespace cosmicpe\worldbuilder\editor\task;

use cosmicpe\worldbuilder\editor\task\utils\ChunkIteratorCursor;
use Generator;
use pocketmine\math\Vector3;
use pocketmine\world\format\Chunk;

abstract class AdvancedChunkEditorTask extends EditorTask{

	public function run() : Generator{
		$first = $this->selection->getPoint(0);
		$second = $this->selection->getPoint(1);

		$min = Vector3::minComponents($first, $second);
		$min_x = $min->x >> Chunk::COORD_BIT_SIZE;
		$min_z = $min->z >> Chunk::COORD_BIT_SIZE;

		$max = Vector3::maxComponents($first, $second);
		$max_x = $max->x >> Chunk::COORD_BIT_SIZE;
		$max_z = $max->z >> Chunk::COORD_BIT_SIZE;

		$cursor = new ChunkIteratorCursor($this->getWorld());
		for($cursor->chunkX = $min_x; $cursor->chunkX <= $max_x; ++$cursor->chunkX){
			for($cursor->chunkZ = $min_z; $cursor->chunkZ <= $max_z; ++$cursor->chunkZ){
				$chunk = $cursor->world->loadChunk($cursor->chunkX, $cursor->chunkZ);
				if($chunk !== null){
					$cursor->chunk = $chunk;
					if($this->onIterate($cursor)){
						$this->onChunkChanged($cursor);
					}
				}
				yield true;
			}
		}
	}

	/**
	 * @param ChunkIteratorCursor $cursor
	 * @return bool whether chunk was changed
	 */
	abstract protected function onIterate(ChunkIteratorCursor $cursor) : bool;
}