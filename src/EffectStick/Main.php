<?php

namespace EffectStick;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase
{
    use SingletonTrait;

    public static $settings;

    protected function onEnable(): void
    {
        self::$instance = $this;
        $this->saveResource("settings.yml");
        self::$settings = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getLogger()->notice("This plugin was made by Fanouu. All Right Reserved");
    }

    public static function getSettings(): Config{
        return self::$settings;
    }

}