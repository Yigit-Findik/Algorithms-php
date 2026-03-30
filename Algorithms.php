<?php

require_once "Node.php";
require_once "Grid.php";
require_once "TileType.php";
require_once "TileMark.php";

class Algorithms {

    // EXAMPLE ALGORITM
    public static function singleRandomLocation($grid): Grid {
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

    // EXAMPLE ALGORITM
    public static function yigitsAlgorithm($grid): Grid {
        $nodes = Algorithms::initialiseOriginList($grid); // gives origin noddde
        $destinations = $grid->getDestinations();

        $origin = $nodes[0];
        $destination = $destinations[0];

        // mihj algoritme

        return $grid;
    }

    // -------------------------------------------------------------------------
    // A* ALGORITHM
    // Finds the shortest path from origin to destination by combining the actual
    // cost from the start (g-score) with a heuristic estimate to the goal
    // (h-score). Uses Manhattan distance as the heuristic, which works perfectly
    // for both orthogonal and diagonal movement on this grid.
    //
    // How it works:
    //  1. Start at the origin. Assign it g=0 and f = heuristic to destination.
    //  2. Pick the open node with the lowest f-score (most promising).
    //  3. If that node IS the destination, we are done – trace back the path.
    //  4. Otherwise, evaluate every non-blocked neighbour:
    //       - tentative_g  = current g + 1  (every step costs 1)
    //       - If this is a cheaper route to the neighbour, update its scores,
    //         record current node as its "previous", and add it to the open set.
    //  5. Move current node to the closed set so it is never re-evaluated.
    //  6. Repeat until destination is reached or open set is empty (no path).
    // -------------------------------------------------------------------------
    public static function aStar($grid): Grid {
        $nodes = Algorithms::initialiseOriginList($grid);
        $destinations = $grid->getDestinations();
 
        // Nothing to do without both endpoints
        if (empty($nodes) || empty($destinations)) {
            return $grid;
        }
 
        $origin = $nodes[0];
        $destination = $destinations[0];
 
        // unique string key for a node so we can use it in associative arrays
        $key = fn(Node $n): string => $n->getX() . ',' . $n->getY();
 
        // Manhattan distance heuristic admissible for both orthogonal and diagonal grids
        $heuristic = fn(Node $a, Node $b): int =>
            abs($a->getX() - $b->getX()) + abs($a->getY() - $b->getY());
 
        // g-score cheapest known cost from origin to each node
        $gScore = [$key($origin) => 0];
 
        // f-score g + heuristic (our best guess for total path cost through this node)
        $fScore = [$key($origin) => $heuristic($origin, $destination)];
 
        // Open set nodes discovered but not yet fully evaluated
        $openSet = [$key($origin) => $origin];
 
        // Closed set nodes already evaluated (never revisit)
        $closedSet = [];
 
        while (!empty($openSet)) {
 
            // Pick the open node with the lowest f-score
            $currentKey = array_reduce(
                array_keys($openSet),
                fn($carry, $k) => ($carry === null || ($fScore[$k] ?? PHP_INT_MAX) < ($fScore[$carry] ?? PHP_INT_MAX))
                    ? $k
                    : $carry,
                null
            );
            $current = $openSet[$currentKey];
 
            // Destination reached mark it and trace the path back
            if ($current === $destination) {
                $destination->setTileMark(TileMark::DESTINATIONPATH);
                Algorithms::setPath($destination);
                return $grid;
            }
 
            // Move current from open -> closed
            unset($openSet[$currentKey]);
            $closedSet[$currentKey] = true;
 
            // Evaluate each neighbour
            foreach ($current->getNeighbours() as $neighbour) {
                $nKey = $key($neighbour);
 
                // Skip already-evaluated or blocked nodes
                if (isset($closedSet[$nKey]))                         continue;
                if ($neighbour->getTileMark() === TileMark::BLOCKED)  continue;
 
                $tentativeG = ($gScore[$currentKey] ?? PHP_INT_MAX) + 1;
 
                // If we found a cheaper route to this neighbour, update it
                if ($tentativeG < ($gScore[$nKey] ?? PHP_INT_MAX)) {
                    $neighbour->setPrevious($current);
                    $gScore[$nKey] = $tentativeG;
                    $fScore[$nKey] = $tentativeG + $heuristic($neighbour, $destination);
 
                    if (!isset($openSet[$nKey])) {
                        // Only colour non-special tiles so origin/destination keep their look
                        if ($neighbour->getTileType() === TileType::EMPTY) {
                            $neighbour->setTileMark(TileMark::PATH);
                        }
                        $openSet[$nKey] = $neighbour;
                    }
                }
            }
        }
 
        // Open set exhausted with no path found (destination is unreachable)
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