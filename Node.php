<?php

require_once "TileMark.php";
require_once "TileType.php";
require_once "Direction.php";

class Node{
    private $x;
    private $y;
    private $type;
    private $mark = TileMark::UNMARKED;
    private $neighbours = [];
    // for recalculating paths
    private $previous = null;
    // for shortest path
    private $mindist = -9*9; // assuming the 9 bound

    public function __construct($x, $y, $tileType = TileType::EMPTY) {
        $this->x = $x;
        $this->y = $y;
        $this->type = $tileType;
    }

    public function getX(): int{
        return $this->x;
    }

    public function getY(): int{
        return $this->y;
    }

    public function setTileMark($mark){
        $this->mark = $mark;
    }

    public function getTileMark(): TileMark {
        return $this->mark;
    }

    public function isEmpty(): bool {
        return $this->type === TileType::EMPTY;
    }

    public function isUnMarked(): bool {
        return $this->mark === TileMark::UNMARKED;
    }

    public function addNeighbour($direction, $node){
        $this->neighbours[$direction] = $node;
    }

    public function nextNeighbour($direction): Node{
        return $this->neighbours[$direction];
    }

    public function getNeighbours(): array{
        return $this->neighbours;
    }

    public function getTileType(): TileType  {
        return $this->type;
    }

    public function setTileType($type){
        $this->type = $type;
    }

    public function setPrevious($node){
        $this->previous = $node;
    }
    public function getPrevious(){
        return $this->previous;
    }
    public function hasPrevious() : bool{
        return isset($this->previous);
    }

    public function getMinDist(): int {
        return $this->mindist;
    }
    public function setMinDist(int $newDist) : void {
        if ($newDist < $this->mindist ){
            $this->mindist = $newDist;
        }
    }

    public function getPreviousDirection(){
        return array_search($this->previous,$this->neighbours,true);
    }
}