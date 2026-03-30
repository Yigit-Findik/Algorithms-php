<?php

class Direction {

    public const DIRECTIONORTHOGONAL = [
        "N" => ["x" => -1,"y" => 0],
        "E" => ["x" => 0,"y" => 1],
        "S" => ["x" => 1,"y" => 0],
        "W" => ["x" => 0,"y" => -1]
    ];

    public const DIRECTION = [
        "N" => ["x" => -1,"y" => 0],
        "NE" => ["x" => -1,"y" => 1],
        "E" => ["x" => 0,"y" => 1],
        "SE" => ["x" => 1,"y" => 1],
        "S" => ["x" => 1,"y" => 0],
        "SW" => ["x" => 1,"y" => -1],
        "W" => ["x" => 0,"y" => -1],
        "NW" => ["x" => -1,"y" => -1]
    ];
}