<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algoritmiek Workshop</title>

    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Algoritmiek workshop</h1>

    <p>
        
<form method="post">
  <label for="orthogonal">Orthogonal:</label>
  <input type="checkbox" name="orthogonal" id="orthogonal" <?php if (isset($_POST["orthogonal"])) echo('checked'); ?>>
  <input type="submit" name="action" value="All Random Grid">
  <input type="submit" name="action" value="Random Same Grid">
  <input type="submit" name="action" value="Fixed Same Grid">
</form>
        <!-- <button><a href=".">Random All Grid</a></button>
        <button><a href=".?same=yes">Random Same Grid</a></button>
        <button><a href=".?same=set">Fixed Same Grid</a></button>
        <input type="checkbox" name="orthogonal" id="orthogonal"> -->
    </p>
    <main>
    
        <?php
        require_once "Grid.php";
        require_once "Algorithms.php";

        $nodeList;
        $blockList;

        $grid = initGrid();
        Algorithms::singleRandomLocation($grid);
        $grid->paintGrid("Single Random Location");


        // When adding a new grid, create a new algorithm method in Algorithms.php following the instructions there.
        // Call the method here as shown above, after which you paint it using the paintGrid method of the class Grid.
        // For example:
        // $grid = new Grid();
        // Algorithms::<your new algorithm method name>($grid);
        // $grid->paintGrid("<name of your algorithm>");


        //The following method is used to initialise grids based on the querystring.        
        function initGrid(): Grid { 
            $grid = new Grid(isset($_POST["orthogonal"]));
            if (!isset($_POST["action"]) || $_POST["action"] == "All Random Grid") {
                $nodes = $grid->getRandomOriginDestinationNodes();
                $grid->setNodeTypes($nodes,[]);
                return $grid;
            }
            global $nodeList, $blockList;
            if($_POST["action"] == "Random Same Grid"){
                if (!isset($nodeList)){
                    $nodeList = $grid->getRandomOriginDestinationNodes();
                }
                if (!isset($blockList)){
                    $blockList = $grid->getBlockNodes(random_int(0,10));
                }
                $grid->setNodeTypes($nodeList,$blockList);
                return $grid;
            }
            // Fixed set of nodes for testing
            if (!isset($nodeList)){
                $nodeList = ["origin"=>[0,0],"destination"=>[8,8]];
            }
            if (!isset($blockList)){
                $blockList = [[0,1],[1,1],[2,2],[3,1],[6,6],[5,7],[5,5],[8,3],[7,3],[6,3],[5,3],[4,3],[2,4],[3,5]];
            }
            $grid->setNodeTypes($nodeList,$blockList);
            return $grid;
        }

       ?>
    </main>
</body>
</html>