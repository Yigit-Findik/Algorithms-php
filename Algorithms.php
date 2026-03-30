<?php

require_once "Node.php";
require_once "Grid.php";
require_once "TileType.php";
require_once "TileMark.php";

class Algorithms {

    // EXAMPLE ALGORITM
    public static function singleRandomLocation($grid): Grid{
        // Random Neighbouring Coordinate Node. 
        // Starting with the origin(s), randomly choose a location next to the node and mark it if unmarked.
        // Add the chosen location to the list of nodes to select and continue to remove a node from the list
        // and mark a random location next to it.
        $nodes = Algorithms::initialiseOriginList($grid);

        $node = null;
        while (!empty($nodes)) {
            $randIndex = random_int(0, count($nodes)-1);
            $node = $nodes[$randIndex];
            $neighbour = $grid->selectRandomNextNode($node);

            array_splice($nodes, $randIndex, 1);

            if($neighbour->getTileMark() === TileMark::UNMARKED){
                $neighbour->setTileMark(TileMark::PATH);
                $neighbour->setPrevious($node);
                $nodes[] = $neighbour;
            }
        }

        Algorithms::setPath($node);
        return $grid;
    }

    // Add your own methods below. Each new method for an algorithm must 
    // be written in the follow way:
    // 
    // public static function <your algorithm's name>($grid): Grid{
    //      $nodes = Algorithms::initialiseOriginList($grid); // to get the origin of your path.
    //      
    //      <add your own algoritm code.>
    //  
    //      return $grid; // because an algoritm method must return a grid.
    // } 

    












    // Don't touch the methods below. These are used to initialise the lists and queues 
    // with origins and/or destinations that you can then use in your algoritm.
    private static function initialiseOriginList(Grid $grid): array {
        // This method returns an array with the origin of your path in it.
        $nodes = [];
        
        foreach($grid->getOrigins() as $origin ){
            $origin->setTileMark(TileMark::ORIGINPATH);
            $nodes[] = $origin;
        }

        return $nodes;
    }

    private static function setPath(Node $lastNode) : void {
        while ($lastNode->hasPrevious()){
            $lastNode->setTileMark(TileMark::CALCPATH);
            $lastNode = $lastNode->getPrevious();
        }
    }
    
}