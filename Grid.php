<?php

require_once "Node.php";
require_once "Direction.php";
require_once "TileType.php";
require_once "TileMark.php";

class Grid{

    private $size = 0;
    private const MAX_SIZE = 9;
    private $grid = [[]];
    private $origins = [];
    private $destinations = [];
    private $directions = Direction::DIRECTION;

    public function __construct($orthogonal = false, $size = 9){
        if ($orthogonal){
            $this->directions = Direction::DIRECTIONORTHOGONAL;
        }
        // only allow sizes from 0 to 9
        $this->size = max(min($size, $this::MAX_SIZE), 0); 
        for($x = 0; $x < $size; $x++){
            for($y = 0; $y < $size; $y++){
                $this->grid[$x][$y] = new Node($x, $y);
            }
        }       

        $this->connectNeighbours();
    }
    
    public function getSize(): int {
        return $this->size;
    }

    public function getOrigins(): array {
        return $this->origins;
    }

    public function getDestinations(): array {
        return $this->destinations;
    }

    public function setDirections($directions) : void {
        $this->directions = $directions;
    }

    public function setNodeTypes($nodeList,$blockList): void {
        foreach ($nodeList as $tileType => $node ){
            $this->addNode($node[0],$node[1],$tileType == 'origin' ? TileType::ORIGIN : TileType::DESTINATION);            
        }
        foreach ($blockList as $node){
            $this->addNode($node[0],$node[1],TileType::BLOCKED);            
        }
    }

    public function getRandomOriginDestinationNodes(): array {
        // set random origin and destination
        $origin = $this->getRandomNode();
        do {
            $destination = $this->getRandomNode();
        }
        while(($origin == $destination));
        return ["origin"=>[$origin->getX(),$origin->getY()],"destination"=>[$destination->getX(),$destination->getY()]];
    }

    public function getBlockNodes(int $amount): array{
        $out = [];
        for ($i = 0 ; $i < $amount ; $i ++){
            $node = $this->getRandomNode();
            $out[] = [$node->getX(),$node->getY()];
        }
        return $out;
    }

    public function getRandomNode(): Node {
        // Returns a random node from the grid
        return $this->grid[random_int(0, ($this->size-1))][random_int(0, ($this->size-1))];
    }

    public function selectRandomNextNode(Node $node): Node {
        // Use this for ALG 0, where we do not use a neighbour construct
        $x = $node->getX();
        $y = $node->getY();
        if((0 <= $x && $x < $this->size) && (0 <= $y && $y < $this->size)){

            if($this->grid[$x][$y] == $node){
                $nodeOptions = [];

                foreach($this->directions as $dirCoords){
                    $xCoord = $x + $dirCoords["x"];
                    $yCoord = $y + $dirCoords["y"];

                    if($this->boundsCheck($xCoord, $yCoord)){
                        $nodeOptions[] = $this->grid[$xCoord][$yCoord];
                    }
                }

                return $nodeOptions[array_rand($nodeOptions, 1)];
            }
        }

        return $node;
    }

    private function connectNeighbours(){
        // Use this for ALG 1 and up, where we use a neighbour construct
        for($x = 0; $x < $this->size; $x++){
            for( $y = 0; $y < $this->size; $y++){
                foreach($this->directions as $direction=>$dirCoords){
                    // check bounds
                    if($this->boundsCheck($x+$dirCoords['x'], $y+$dirCoords['y'])){
                        // neighbour coordinates still in the field
                        $neighbour = $this->grid[$x+$dirCoords['x']][$y+$dirCoords['y']];

                        $this->grid[$x][$y]->addNeighbour($direction, $neighbour);
                    }
                }
            }
        }
    }

    public function addNode($x, $y, $type): void {
        if ($this->boundsCheck($x,$y) && $this->grid[$x][$y]->getTileType() === TileType::EMPTY){
            $node = $this->grid[$x][$y];
            $node->setTileType($type);
            match($type) {
                TileType::ORIGIN =>
                    $this->origins[] = $this->grid[$x][$y], 
                TileType::DESTINATION =>
                    $this->destinations[] = $this->grid[$x][$y],
                TileType::BLOCKED =>
                    $node->setTileMark(TileMark::BLOCKED)
            };
        }
    }  

    public function boundsCheck($x,$y): bool{
        return $x >= 0 && $x < $this->size && $y >= 0 && $y < $this->size;
    }

    public function paintGrid($name = "Test grid"){
        echo "<div class='container'>";
        echo "<h3 class='grid-header'>$name</h3>";
        echo "<div class='grid'>";

        for($x = 0; $x < $this->size; $x++){
            for($y = 0; $y < $this->size; $y++){
                $node = $this->grid[$x][$y];
                $tileType = $node->getTileType()->value;
                $tileMark = $node->getTileMark()->value;
                $tilePreviousDirection = $node->getPreviousDirection();
                echo "<div class='grid-item tile-$tileType colour-$tileMark'>";
                    if(str_contains($tileMark,"path")){
                        echo "<i class='previous-$tilePreviousDirection'></i>"; 
                    }
                echo "</div>";
            }
        }

        echo "</div>";
        echo "</div>";

    }
}