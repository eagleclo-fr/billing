<?php
namespace src\User\addons;

use PDO;

class PaginatedQuery{

    private $query;
    private $queryCount;
    private $pdo;
    private $perPage;

    private $count;
    private $items;

    public function __construct(string $query,string $queryCount,PDO $pdo,int $perPage=12)
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->pdo = $pdo;
        $this->perPage = $perPage;
    }
    public function getItems():array
    {
        if($this->items === null){
            $currentPage = $this->getCurrentPage();
            $pages = $this->getPages();
            if($currentPage > $pages){
                return [];
            }
            $offset = $this->perPage * ($currentPage -1);
            $this->items = $this->pdo->query(
                $this->query.
                " LIMIT {$this->perPage} OFFSET $offset"
            )->fetchAll(PDO::FETCH_OBJ);
        }
        return $this->items;
    }



    public function previousLink(string $link):?string{
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;
        if($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return <<<HTML
        <a href="{$link}" class="btn btn-outline-primary">&laquo; Page précédente</a>
HTML;
    }
    public function nextLink(string $link):?string{
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if($currentPage >= $pages) return null;
        $link .= "?page=". ($currentPage + 1);
        return <<<HTML
            <a href="{$link}" class="btn btn-outline-secondary"> Page suivante &raquo;</a>
HTML;
    }

    private function getCurrentPage():int
    {
        return self::getPositiveInt('page', 1);
    }
    private function getPages()
    {
        if($this->count === null){
            $this->count = (int) $this->pdo
                ->query($this->queryCount)
                ->fetch(\PDO::FETCH_NUM)[0];
        }
        return ceil($this->count / $this->perPage);
    }

    public static function getInt(string $name, ?int $default=null):?int
    {
        $named = $_GET[$name] ?? null;
        if(!isset($named)) return $default;
        if($named === '0')return 0;
        if(!filter_var($named,FILTER_VALIDATE_INT)){
            throw new Exception("Le paramètre ".$name. " n'est pas un entier !");
        }
        return $named;
    }
    public static function getPositiveInt(string $name, ?int $default=null):?int
    {
        $param = self::getInt($name, $default);
        if($param !== null && $param <= 0){
            throw new Exception("Le paramètre ".$name.  " n'est pas un entier positif!");
        }
        return $param;
    }
}