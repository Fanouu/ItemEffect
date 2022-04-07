<?php

namespace EffectStick;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\StringToEffectParser;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class EventListener implements Listener{

    public static $coold = [];

    /**
     * @handleCancelled
     */
    public function onUse(PlayerItemUseEvent $event){
        $all = Main::getSettings()->getAll();
        $player = $event->getPlayer();
        $item = $event->getItem();

        foreach ($all as $index => $value){
            $exp = explode(":", $value["id"]);
            if((int)$exp[0] === $item->getId() && (int)$exp[1] === $item->getMeta() && $value["type"] === "click"){
                if(isset(self::$coold[$player->getName()][$value["id"]]) && self::$coold[$player->getName()][$value["id"]] - time() > 0){
                    $player->sendMessage(str_replace("{time}", self::$coold[$player->getName()][$value["id"]] - time(), $value["cooldownMessage"]));
                    return;
                }
                foreach ($value["effects"] as $indexs => $effectII){
                    $effectI = explode(",", $effectII);
                    $effects = StringToEffectParser::getInstance()->parse($effectI[0]);
                    $player->getEffects()->add(new EffectInstance($effects, (int)$effectI[1]*20, (int)$effectI[2]-1));
                }
                self::$coold[$player->getName()][$value["id"]] = time() + $value["cooldown"];
                break;
            }
        }
    }

    /**
     * @handleCancelled
     */
    public function itemConsume(PlayerItemConsumeEvent $event){
        $all = Main::getSettings()->getAll();
        $player = $event->getPlayer();
        $item = $event->getItem();
        foreach ($all as $index => $value){
            $exp = explode(":", $value["id"]);
            if((int)$exp[0] == $item->getId() && (int)$exp[1] == $item->getMeta() && $value["type"] === "consume"){
                var_dump(0);
                if(isset(self::$coold[$player->getName()][$value["id"]]) && self::$coold[$player->getName()][$value["id"]] - time() > 0){
                    $player->sendMessage(str_replace("{time}", self::$coold[$player->getName()][$value["id"]] - time(), $value["cooldownMessage"]));
                    return;
                }
                foreach ($value["effects"] as $indexs => $effectII){
                    $effectI = explode(",", $effectII);
                    $effect = StringToEffectParser::getInstance()->parse($effectI[0]);
                    $player->getEffects()->add(new EffectInstance($effect, (int)$effectI[1]*20, (int)$effectI[2]-1));
                }
                self::$coold[$player->getName()][$value["id"]] = time() + $value["cooldown"];
                break;
            }
        }
    }
}