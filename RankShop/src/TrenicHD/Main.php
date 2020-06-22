<?php

namespace TrenicHD;

use pocketmine\block\Sandstone;
use pocketmine\entity\Effect;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\level\Position;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\level\sound\PopSound;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacketV2;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use jojoe77777\FormAPI;
use pocketmine\entity\EffectInstance;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\level\sound\AnvilUseSound;
use onebone\economyapi\EconomyAPI;


class Main extends PluginBase implements Listener
{

    public $prefix = "§bRankShop";


    public function onLoad()
    {
        $this->getLogger()->info(TextFormat::AQUA . "Plugin $this->prefix Geladen!");
    }

    public function onEnable()
    {

        $this->getLogger()->info(TextFormat::GREEN . "Plugin $this->prefix wurde Geladen!");

    }

    public function onDisable()
    {

        $this->getLogger()->error(TextFormat::RED . "Plugin $this->prefix deaktiviert!");

    }

    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args): bool
    {

        switch ($cmd->getName()) {
            case "rankshop":
                if ($player instanceof Player) {
                    if ($player->hasPermission("rs.use")) {
                        $this->Shop($player);
                    } else {
                        $player->sendMessage(TextFormat::RED . "$this->prefix Keine Rechte!");
                    }
                }
                break;
        }
        return true;

    }

    public function Premium($player)
    {
        $m = EconomyAPI::getInstance()->myMoney($player);
        if ($m >= 20000){
            EconomyAPI::getInstance()->reduceMoney($player, 20000);
            $purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
            $group = $purePerms->getGroup("Premium");
            $purePerms->setGroup($player, $group);
            $player->sendMessage("§b Du hast denn $group Rang gekauft!");
        } else {
            $player->sendMessage("§cOups... Dein Konto hat nicht genug Guthaben! :c");
        }
    }


    public function Premiumplus($player)
    {
        $m = EconomyAPI::getInstance()->myMoney($player);
        if ($m >= 40000){
            EconomyAPI::getInstance()->reduceMoney($player, 40000);
            $purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
            $group = $purePerms->getGroup("PremiumPlus");
            $purePerms->setGroup($player, $group);
            $player->sendMessage("§b Du hast denn $group Rang gekauft!");
        } else {
            $player->sendMessage("§cOups... Dein Konto hat nicht genug Guthaben! :c");
        }
    }


    public function Hero($player)
        {
            $m = EconomyAPI::getInstance()->myMoney($player);
            if ($m >= 60000){
                EconomyAPI::getInstance()->reduceMoney($player, 60000);
                $purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
                $group = $purePerms->getGroup("Hero");
                $purePerms->setGroup($player, $group);
                $player->sendMessage("§b Du hast denn $group Rang gekauft!");
            } else {
                $player->sendMessage("§cOups... Dein Konto hat nicht genug Guthaben! :c");
            }
        }
            function Supreme($player)
            {

                $m = EconomyAPI::getInstance()->myMoney($player);
                if ($m >= 80000){
                    EconomyAPI::getInstance()->reduceMoney($player, 80000);
                    $purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
                    $group = $purePerms->getGroup("Supreme");
                    $purePerms->setGroup($player, $group);
                    $player->sendMessage("§b Du hast denn $group Rang gekauft!");
                } else {
                    $player->sendMessage("§cOups... Dein Konto hat nicht genug Guthaben! :c");
                }
            }


    public function Shop($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null){
                return true;
            }
            switch ($result){
                case 0:
                    $this->Premium($player);
                    break;
            }
            switch ($result) {
                case 1:
                    $this->Premiumplus($player);
                    break;
            }
            switch ($result) {
                case 2:
                    $this->Hero($player);
                    break;
            }
            switch ($result) {
                case 3:
                    $this->Supreme($player);
                    break;
            }
            switch ($result){
                case 4:
                    break;
            }
        });
        $form->setTitle("$this->prefix");
        $form->addButton("§6Premium \n §0[20k]");
        $form->addButton("§5Premium+  \n §0[40k]");
        $form->addButton("§4Hero  \n §0[60k]");
        $form->addButton("§bSupreme  \n §0[80k]");
        $form->addButton("§cVerlassen");
        $form->sendToPlayer($player);
        return true;
    }

}