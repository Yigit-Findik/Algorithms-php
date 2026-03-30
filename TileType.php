<?php

enum TileType: string{
    case EMPTY = "empty";
    case ORIGIN = "origin";
    case DESTINATION = "destination";
    case BLOCKED = 'blocked';
}