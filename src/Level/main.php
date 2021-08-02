<?php

namespace Level;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\block\BlockbreakEvent;
use pocketmine\utils\Config;

class main extends PluginBase implements Listener{

   public function onEnable(){
      $this->getserver()->getPluginManager()->registerEvents($this, $this);
      if(!file_exists($this->getDataFolder())){
         mkdir($this->getdataFolder(), 0744, true);
      }
      $this->data = new Config($this->getDataFolder() ."data.yml", Config::YAML);
      $this->config = new Config($this->getDataFolder() ."config.yml", Config::YAML,array(
      'ブロック破壊数' => '6400'
      ));
   }
   
   public function onBreak(BlockBreakEvent $event){
      $player = $event->getPlayer();
      $name = $player->getName();
      $data = $this->data->get($name);
      $this->data->set($name,$data+1);
      $this->data->save();
      $config = $this->config->get('ブロック破壊数');
      if($data % $config === 0){
         MoneyLevelAPI::getInstance()->lvup($name); //レベルアップ処理
         $player->sendMessage("§e【運営】 §fレベルが上がりました！");
      }
   }
}
