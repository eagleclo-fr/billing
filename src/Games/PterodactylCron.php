<?php
namespace src\Games;

use src\Games\PterodactylGames;
use src\Database\Database;
use src\Games\Database\GamesTable;

class PterodactylCron
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->PterodactylGames = new PterodactylGames();
        $this->gamesTable = new GamesTable();
    }

    public function run()
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `games_servers` WHERE status = :status LIMIT 1');
        $this->select->bindValue(':status', 'pending', $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            echo 'ID GAME TABLE : '.$this->result['id'].'<br>';

            $this->idservice = $this->result['idservice'];
            $this->userid = $this->result['userid'];
            $this->cpuPterodactyl = $this->result['cores'];
            $this->memoryPterodactyl = $this->result['ram'];
            $this->diskPterodactyl = $this->result['space'];

            $this->gamesTable->getGamesTable($this->idservice);
            $this->gamesTable->getGamesOfferTable($this->gamesTable->offer);

            $this->PterodactylGames->createServer(
                $this->userid,
                $this->gamesTable->type_games,
                $this->gamesTable->nestPterodactyl,
                $this->gamesTable->eggPterodactyl,
                $this->gamesTable->docker_imagePterodactyl,
                $this->gamesTable->startupPterodactyl,
                $this->memoryPterodactyl,
                $this->gamesTable->swap,
                $this->diskPterodactyl,
                $this->cpuPterodactyl,
                $this->gamesTable->backupsPterodactyl,
                $this->gamesTable->allocationsPterodactyl,
                $this->gamesTable->backupsPterodactyl,
                $this->gamesTable->allocationsPterodactyl,
                $this->gamesTable->start_on_completion
            );

            $this->internal_id = $this->PterodactylGames->internal_id;
            $this->external_id = $this->PterodactylGames->external_id;

            $this->gamesTable->updateInstall($this->idservice, $this->internal_id, $this->external_id);

        }

    }

}