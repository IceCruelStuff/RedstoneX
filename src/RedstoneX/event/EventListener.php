<?php

declare(strict_types=1);

namespace RedstoneX\event;

use pocketmine\block\Block;
use pocketmine\block\Solid;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use RedstoneX\block\Redstone;
use RedstoneX\block\RedstoneTorch;
use RedstoneX\RedstoneX;

/**
 * Class EventListener
 * @package RedstoneX\event
 */
class EventListener implements Listener {

	/**
	 * @param BlockPlaceEvent $event
	 */
	public function onPlace(BlockPlaceEvent $event) {
		$block = $event->getBlock();
		if(!($block->getLevel()->getBlock($block->add(0, -1, 0)) instanceof Solid)) {
			$event->setCancelled();
		}
		switch ($event->getBlock()->getId()) {
			case Block::REDSTONE_TORCH:
				$event->setCancelled(true);
				$event->getBlock()->getLevel()->setBlock($event->getBlock()->asVector3(), new RedstoneTorch(0), false, true);
				if ($block instanceof RedstoneTorch) {
					RedstoneX::consoleDebug("Placing block (Redstone Torch) (RedstoneX block)");
					$block->activateRedstone();
				} else {
					RedstoneX::consoleDebug("Placing block (Redstone Torch) (pmmp block)");
					if ($event->getBlock()->getLevel()->getBlock($event->getBlock()->asVector3()) instanceof RedstoneTorch) {
						RedstoneX::consoleDebug("Placed block (Redstone Torch) (pmmp block)");
					}
				}
				return;
			case RedstoneX::REDSTONE_ITEM:
			case Block::REDSTONE_WIRE:
				if ($event->isCancelled()) {
					$event->setCancelled(false);
				}
				$event->getBlock()->getLevel()->setBlock($event->getBlock()->asVector3(), new Redstone(RedstoneX::REDSTONE_WIRE, $event->getItem()->getDamage()), false, true);
				$event->setCancelled(true);
				if ($block instanceof Redstone) {
					RedstoneX::consoleDebug("Placing block (Redstone Wire) (RedstoneX block)");
					$block->activateRedstone();
				} else {
					RedstoneX::consoleDebug("Placing block (Redstone Wire) (pmmp block)");
				}
				return;
		}
	}
}