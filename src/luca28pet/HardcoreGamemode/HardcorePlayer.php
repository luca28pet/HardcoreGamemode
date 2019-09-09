<?php

namespace luca28pet\HardcoreGamemode;

use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\level\Position;
use pocketmine\Player;

class HardcorePlayer extends Player{

    public function respawn() : void{
        $ev = new PlayerRespawnEvent($this, $this->getSpawn());
        $ev->call();

        $realSpawn = Position::fromObject($ev->getRespawnPosition()->add(0.5, 0, 0.5), $ev->getRespawnPosition()->getLevel());
        $this->teleport($realSpawn);

        $this->setSprinting(false);
        $this->setSneaking(false);

        $this->extinguish();
        $this->setAirSupplyTicks($this->getMaxAirSupplyTicks());
        $this->deadTicks = 0;
        $this->noDamageTicks = 60;

        $this->removeAllEffects();
        $this->setHealth($this->getMaxHealth());

        foreach($this->attributeMap->getAll() as $attr){
            $attr->resetToDefault();
        }

        $this->sendData($this);
        $this->sendData($this->getViewers());

        $this->sendSettings();
        $this->sendAllInventories();

        $this->spawnToAll();
        $this->scheduleUpdate();

        if($this->server->isHardcore()){
            $this->setGamemode(Player::SPECTATOR);
        }
    }

}