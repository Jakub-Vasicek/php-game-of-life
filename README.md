# Game of Life

PHP implementation of [Game of Life](https://en.wikipedia.org/wiki/Conway%27s_Game_of_Life).
## Setup

### Building up
```
docker compose up -d
```

### Running the container
```
docker compose exec php-gol bash
```

## How to run application
```
php run.php game:run -i input.xml -o output.xml
```

Parameter ```-i``` is optional, default value is ```input.xml```.
Parameter ```-o``` is optional, default value is ```output.xml```.

## Sample input
```xml
<?xml version="1.0"?>
<life>
    <world>
        <width>4</width> <!-- Width of the "world" -->
        <height>4</height> <!-- Width of the "world" -->
        <speciesCount>1</speciesCount> <!-- Number of distinct species -->
        <iterations>10</iterations> <!-- Number of iterations to be calculated -->
    </world>
    <organisms>
        <organism>
            <x_pos>2</x_pos> <!-- x position -->
            <y_pos>0</y_pos> <!-- y position -->
            <speciesType>0</speciesType> <!-- Species type -->
        </organism>
        <organism>
            <x_pos>0</x_pos>
            <y_pos>1</y_pos>
            <speciesType>0</speciesType>
        </organism>
        <organism>
            <x_pos>3</x_pos>
            <y_pos>1</y_pos>
            <speciesType>0</speciesType>
        </organism>
        <organism>
            <x_pos>0</x_pos>
            <y_pos>2</y_pos>
            <speciesType>0</speciesType>
        </organism>
        <organism>
            <x_pos>3</x_pos>
            <y_pos>2</y_pos>
            <speciesType>0</speciesType>
        </organism>
        <organism>
            <x_pos>1</x_pos>
            <y_pos>3</y_pos>
            <speciesType>0</speciesType>
        </organism>
    </organisms>
</life>
```

## How to run tests

Tests are written in PHPUnit.

```
composer test
```
