<?php

define('ROW',8);
define('COL',8);

$grid
= [
    [ 1, 0, 1, 1, 1, 1, 0, 1],
    [ 1, 1, 1, 1, 1, 1, 1, 0],
    [ 0, 1, 1, 1, 1, 1, 1, 1],
    [ 1, 1, 1, 0, 1, 1, 1, 1],
    [ 1, 1, 1, 0, 0, 1, 1, 1],
    [ 1, 0, 1, 1, 1, 1, 1, 1],
    [ 1, 1, 1, 1, 1, 1, 1, 0],
    [ 1, 0, 1, 1, 1, 1, 1, 1]
 ];

aStarSearch([7,4],[1,3]);




function isValid(int $row, int $col)
{
    // Returns true if row number and column number
    // is in range
    // var_dump($row);
    // die;
    return ($row >= 0) && ($row <= ROW - 1) && ($col >= 0)
           && ($col <= COL - 1);
}

// A Utility Function to check whether the given cell is
// blocked or not
function isUnBlocked(int $row, int $col)
{
    global $grid;
    // Returns true if the cell is not blocked else false
    if ($grid[$row][$col] == 1)
        return (true);
    else
        return (false);
}


function calculateHValue(int $row, int $col, $dest)
{
    // Return using the distance formula
    return ((double)sqrt(
        ($row - $dest[0]) * ($row - $dest[0])
        + ($col - $dest[1]) * ($col - $dest[1])));
}


// A Utility Function to check whether destination cell has
// been reached or not
function isDestination(int $row, int $col, $dest)
{
    if ($row == $dest[0] && $col == $dest[1]){
        // var_dump($row,$col);
        return (true);
    }else{
        return (false);
    }

}


// A Utility Function to trace the path from the source
// to destination
function tracePath($cellDetails, $dest)
{
    printf("\nThe Path is ");
    $row = $dest[0];
    $col = $dest[1];

    // var_dump($cellDetails);
    // die;
    while (!($cellDetails[$row][$col]['parent_i'] == $row
             && $cellDetails[$row][$col]['parent_j'] == $col)) {
        // Path.push(make_pair(row, col));
        $temp_row = $cellDetails[$row][$col]['parent_i'];
        $temp_col = $cellDetails[$row][$col]['parent_j'];
        $row = $temp_row;
        $col = $temp_col;
        var_dump($row.':'.$col);
    }

    // Path.push(make_pair(row, col));
    // while (!Path.empty()) {
    //     pair<int, int> p = Path.top();
    //     Path.pop();
    //     printf("-> (%d,%d) ", p.first, p.second);
    // }

    return;
}




// A Function to find the shortest path between
// a given source cell to a destination cell according
// to A* Search Algorithm
function aStarSearch($src, $dest)
{
    // $src[0]=$src[0]-1;
    // $src[1]=$src[1]-1;
    // $dest[0]=$dest[0]-1;
    // $dest[1]=$dest[1]-1;
    // var_dump($src);
    // var_dump($dest);

    // If the source is out of range
    if (isValid($src[0], $src[1]) == false) {
        printf("Source is invalid\n");
        return;
    }

    // If the destination is out of range
    if (isValid($dest[0], $dest[1]) == false) {
        printf("Destination is invalid\n");
        return;
    }

    // Either the source or the destination is blocked
    if (isUnBlocked($src[0], $src[1]) == false
        || isUnBlocked($dest[0], $dest[1])
               == false) {
        printf("Source or the destination is blocked\n");
        return;
    }

    // If the destination cell is the same as source cell
    if (isDestination($src[0], $src[1], $dest)
        == true) {
        printf("We are already at the destination\n");
        return;
    }

    // Create a closed list and initialise it to false which
    // means that no cell has been included yet This closed
    // list is implemented as a boolean 2D array


    $closedList= (function(){
        for($k=0;$k <= ROW;$k++){
            $closedList[]=array_fill(0, COL,false);
        }
        return $closedList;
    })();
    // Declare a 2D array of structure to hold the details
    // of that cell
    $cellDetails=[];

    for ($i = 0; $i < ROW; $i++) {
        for ($j = 0; $j < COL; $j++) {
            $cellDetails[$i][$j]['f'] = PHP_FLOAT_MAX;
            $cellDetails[$i][$j]['g'] = PHP_FLOAT_MAX;
            $cellDetails[$i][$j]['h'] = PHP_FLOAT_MAX;
            $cellDetails[$i][$j]['parent_i'] = -1;
            $cellDetails[$i][$j]['parent_j'] = -1;
        }
    }

    // Initialising the parameters of the starting node
    $i = $src[0];
    $j = $src[1];
    $cellDetails[$i][$j]['f'] = 0.0;
    $cellDetails[$i][$j]['g'] = 0.0;
    $cellDetails[$i][$j]['h'] = 0.0;
    $cellDetails[$i][$j]['parent_i'] = $i;
    $cellDetails[$i][$j]['parent_j'] = $j;






        /*
     Create an open list having information as-
     <f, <i, j>>
     where f = g + h,
     and i, j are the row and column index of that cell
     Note that 0 <= i <= ROW-1 & 0 <= j <= COL-1
     This open list is implemented as a set of pair of
     pair.*/

     // Put the starting cell on the open list and set its
     // 'f' as 0
    $openList[]=['f'=>0.0,'pos'=>[$i,$j]];

    // We set this boolean value as false as initially
    // the destination is not reached.
    $foundDest = false;


    while (!empty($openList)) {
        // var_dump($openList);
        // Add this vertex to the closed list
        $min = $openList[0]['f'];
        $minIndex = 0;
        for($c=0;$c<count($openList);$c++){
            $closedList[$openList[$c]['pos'][0]][$openList[$c]['pos'][1]] = true;
            if ($openList[$c]['f'] < $min) {
                $minIndex = $c;
                $min = $openList[$c]['f'];
            }
        }

        $i = $openList[$minIndex]['pos'][0];
        $j = $openList[$minIndex]['pos'][1];

        // Remove this vertex from the open list
        // OpenList.erase(openList.begin());

        // Remove this vertex from the open list
        unset($openList);


        /*
         Generating all the 8 successor of this cell

              N.W   N   N.E
                \   |   /
                 \  |  /
             W----Cell----E
                 / | \
               /   |  \
            S.W    S   S.E

         Cell-->Popped Cell (i, j)
         N -->  North       (i-1, j)
         S -->  South       (i+1, j)
         E -->  East        (i, j+1)
         W -->  West           (i, j-1)
         N.E--> North-East  (i-1, j+1)
         N.W--> North-West  (i-1, j-1)
         S.E--> South-East  (i+1, j+1)
         S.W--> South-West  (i+1, j-1)*/

        // To store the 'g', 'h' and 'f' of the 8 successors

                // Only process this cell if this is a valid one
                if (isValid($i - 1, $j) == true) {
                    // If the destination cell is the same as the
                    // current successor
                    if (isDestination($i - 1, $j, $dest) == true) {
                        // Set the Parent of the destination cell
                        $cellDetails[$i - 1][$j]['parent_i'] = $i;
                        $cellDetails[$i - 1][$j]['parent_j'] = $j;
                        printf("The destination cell is found\n");
                        tracePath($cellDetails, $dest);
                        $foundDest = true;
                        return;
                    }
                    // If the successor is already on the closed
                    // list or if it is blocked, then ignore it.
                    // Else do the following
                    else if (($closedList[$i - 1][$j] == false)
                             && isUnBlocked($i - 1, $j) == true) {
                        $gNew = $cellDetails[$i][$j]['g'] + 3.0;
                        $hNew = calculateHValue($i - 1, $j, $dest);
                        $fNew = $gNew + $hNew;
                        // If it isn’t on the open list, add it to
                        // the open list. Make the current square
                        // the parent of this square. Record the
                        // f, g, and h costs of the square cell
                        //                OR
                        // If it is on the open list already, check
                        // to see if this path to that square is
                        // better, using 'f' cost as the measure.
                        if ($cellDetails[$i - 1][$j]['f'] == PHP_FLOAT_MAX
                            || $cellDetails[$i - 1][$j]['f'] > $fNew) {
                            // $openList.insert(make_pair(
                            //     fNew, make_pair(i - 1, j)));

                            $openList[] = ['f'=>$fNew,'pos'=>[$i - 1,$j]];

                            // Update the details of this cell
                            $cellDetails[$i - 1][$j]['f'] = $fNew;
                            $cellDetails[$i - 1][$j]['g'] = $gNew;
                            $cellDetails[$i - 1][$j]['h'] = $hNew;
                            $cellDetails[$i - 1][$j]['parent_i'] = $i;
                            $cellDetails[$i - 1][$j]['parent_j'] = $j;

                        }
                    }
                }


                // Only process this cell if this is a valid one
                if (isValid($i+1, $j) == true) {

                    // If the destination cell is the same as the
                    // current successor
                    if (isDestination($i + 1, $j, $dest) == true) {
                        // Set the Parent of the destination cell
                        $cellDetails[$i + 1][$j]['parent_i'] = $i;
                        $cellDetails[$i + 1][$j]['parent_j'] = $j;
                        printf("The destination cell is found\n");
                        tracePath($cellDetails, $dest);
                        $foundDest = true;
                        return;
                    }
                    // If the successor is already on the closed
                    // list or if it is blocked, then ignore it.
                    // Else do the following
                    else if (($closedList[$i + 1][$j] == false)
                                && isUnBlocked($i + 1, $j) == true) {
                        $gNew = $cellDetails[$i][$j]['g'] + 3.0;
                        $hNew = calculateHValue($i + 1, $j, $dest);
                        $fNew = $gNew + $hNew;
                        // If it isn’t on the open list, add it to
                        // the open list. Make the current square
                        // the parent of this square. Record the
                        // f, g, and h costs of the square cell
                        //                OR
                        // If it is on the open list already, check
                        // to see if this path to that square is
                        // better, using 'f' cost as the measure.
                        if ($cellDetails[$i + 1][$j]['f'] == PHP_FLOAT_MAX
                            || $cellDetails[$i + 1][$j]['f'] > $fNew) {
                            // $openList.insert(make_pair(
                            //     fNew, make_pair(i - 1, j)));

                            $openList[] = ['f'=>$fNew,'pos'=>[$i + 1,$j]];

                            // Update the details of this cell
                            $cellDetails[$i + 1][$j]['f'] = $fNew;
                            $cellDetails[$i + 1][$j]['g'] = $gNew;
                            $cellDetails[$i + 1][$j]['h'] = $hNew;
                            $cellDetails[$i + 1][$j]['parent_i'] = $i;
                            $cellDetails[$i + 1][$j]['parent_j'] = $j;

                        }
                    }
                }



                // Only process this cell if this is a valid one
                if (isValid($i, $j+1) == true) {
                    // If the destination cell is the same as the
                    // current successor
                    if (isDestination($i, $j+1, $dest) == true) {
                        // Set the Parent of the destination cell
                        $cellDetails[$i][$j+1]['parent_i'] = $i;
                        $cellDetails[$i][$j+1]['parent_j'] = $j;
                        printf("The destination cell is found\n");
                        tracePath($cellDetails, $dest);
                        $foundDest = true;
                        return;
                    }
                    // If the successor is already on the closed
                    // list or if it is blocked, then ignore it.
                    // Else do the following
                    else if (($closedList[$i][$j+1] == false)
                                && isUnBlocked($i, $j+1) == true) {
                        $gNew = $cellDetails[$i][$j]['g'] + 3.0;
                        $hNew = calculateHValue($i, $j+1, $dest);
                        $fNew = $gNew + $hNew;
                        // If it isn’t on the open list, add it to
                        // the open list. Make the current square
                        // the parent of this square. Record the
                        // f, g, and h costs of the square cell
                        //                OR
                        // If it is on the open list already, check
                        // to see if this path to that square is
                        // better, using 'f' cost as the measure.
                        if ($cellDetails[$i][$j+1]['f'] == PHP_FLOAT_MAX
                            || $cellDetails[$i][$j+1]['f'] > $fNew) {
                            // $openList.insert(make_pair(
                            //     fNew, make_pair(i - 1, j)));

                            $openList[] = ['f'=>$fNew,'pos'=>[$i,$j+1]];

                            // Update the details of this cell
                            $cellDetails[$i][$j+1]['f'] = $fNew;
                            $cellDetails[$i][$j+1]['g'] = $gNew;
                            $cellDetails[$i][$j+1]['h'] = $hNew;
                            $cellDetails[$i][$j+1]['parent_i'] = $i;
                            $cellDetails[$i][$j+1]['parent_j'] = $j;

                        }
                    }
                }



                // Only process this cell if this is a valid one
                if (isValid($i, $j-1) == true) {
                    // If the destination cell is the same as the
                    // current successor
                    if (isDestination($i, $j-1, $dest) == true) {
                        // Set the Parent of the destination cell
                        $cellDetails[$i][$j-1]['parent_i'] = $i;
                        $cellDetails[$i][$j-1]['parent_j'] = $j;
                        printf("The destination cell is found\n");
                        tracePath($cellDetails, $dest);
                        $foundDest = true;
                        return;
                    }
                    // If the successor is already on the closed
                    // list or if it is blocked, then ignore it.
                    // Else do the following
                    else if (($closedList[$i][$j-1] == false)
                                && isUnBlocked($i, $j-1) == true) {
                        $gNew = $cellDetails[$i][$j]['g'] + 3.0;
                        $hNew = calculateHValue($i, $j-1, $dest);
                        $fNew = $gNew + $hNew;
                        // If it isn’t on the open list, add it to
                        // the open list. Make the current square
                        // the parent of this square. Record the
                        // f, g, and h costs of the square cell
                        //                OR
                        // If it is on the open list already, check
                        // to see if this path to that square is
                        // better, using 'f' cost as the measure.
                        if ($cellDetails[$i][$j-1]['f'] == PHP_FLOAT_MAX
                            || $cellDetails[$i][$j-1]['f'] > $fNew) {
                            // $openList.insert(make_pair(
                            //     fNew, make_pair(i - 1, j)));

                            $openList[] = ['f'=>$fNew,'pos'=>[$i,$j-1]];

                            // Update the details of this cell
                            $cellDetails[$i][$j-1]['f'] = $fNew;
                            $cellDetails[$i][$j-1]['g'] = $gNew;
                            $cellDetails[$i][$j-1]['h'] = $hNew;
                            $cellDetails[$i][$j-1]['parent_i'] = $i;
                            $cellDetails[$i][$j-1]['parent_j'] = $j;

                        }
                    }
                }


                // Only process this cell if this is a valid one
                if (isValid($i-1, $j+1) == true) {
                    // If the destination cell is the same as the
                    // current successor
                    if (isDestination($i-1, $j+1, $dest) == true) {
                        // Set the Parent of the destination cell
                        $cellDetails[$i-1][$j+1]['parent_i'] = $i;
                        $cellDetails[$i-1][$j+1]['parent_j'] = $j;
                        printf("The destination cell is found\n");
                        tracePath($cellDetails, $dest);
                        $foundDest = true;
                        return;
                    }
                    // If the successor is already on the closed
                    // list or if it is blocked, then ignore it.
                    // Else do the following
                    else if (($closedList[$i-1][$j+1] == false)
                                && isUnBlocked($i-1, $j+1) == true) {
                        $gNew = $cellDetails[$i][$j]['g'] + 3.0;
                        $hNew = calculateHValue($i-1, $j+1, $dest);
                        $fNew = $gNew + $hNew;
                        // If it isn’t on the open list, add it to
                        // the open list. Make the current square
                        // the parent of this square. Record the
                        // f, g, and h costs of the square cell
                        //                OR
                        // If it is on the open list already, check
                        // to see if this path to that square is
                        // better, using 'f' cost as the measure.
                        if ($cellDetails[$i-1][$j+1]['f'] == PHP_FLOAT_MAX
                            || $cellDetails[$i-1][$j+1]['f'] > $fNew) {
                            // $openList.insert(make_pair(
                            //     fNew, make_pair(i - 1, j)));

                            $openList[] = ['f'=>$fNew,'pos'=>[$i-1,$j+1]];

                            // Update the details of this cell
                            $cellDetails[$i-1][$j+1]['f'] = $fNew;
                            $cellDetails[$i-1][$j+1]['g'] = $gNew;
                            $cellDetails[$i-1][$j+1]['h'] = $hNew;
                            $cellDetails[$i-1][$j+1]['parent_i'] = $i;
                            $cellDetails[$i-1][$j+1]['parent_j'] = $j;

                        }
                    }
                }

                // Only process this cell if this is a valid one
                if (isValid($i-1, $j-1) == true) {
                    // If the destination cell is the same as the
                    // current successor
                    if (isDestination($i-1, $j-1, $dest) == true) {
                        // Set the Parent of the destination cell
                        $cellDetails[$i-1][$j-1]['parent_i'] = $i;
                        $cellDetails[$i-1][$j-1]['parent_j'] = $j;
                        printf("The destination cell is found\n");
                        tracePath($cellDetails, $dest);
                        $foundDest = true;
                        return;
                    }
                    // If the successor is already on the closed
                    // list or if it is blocked, then ignore it.
                    // Else do the following
                    else if (($closedList[$i-1][$j-1] == false)
                                && isUnBlocked($i-1, $j-1) == true) {
                        $gNew = $cellDetails[$i][$j]['g'] + 3.0;
                        $hNew = calculateHValue($i-1, $j-1, $dest);
                        $fNew = $gNew + $hNew;
                        // If it isn’t on the open list, add it to
                        // the open list. Make the current square
                        // the parent of this square. Record the
                        // f, g, and h costs of the square cell
                        //                OR
                        // If it is on the open list already, check
                        // to see if this path to that square is
                        // better, using 'f' cost as the measure.
                        if ($cellDetails[$i-1][$j-1]['f'] == PHP_FLOAT_MAX
                            || $cellDetails[$i-1][$j-1]['f'] > $fNew) {
                            // $openList.insert(make_pair(
                            //     fNew, make_pair(i - 1, j)));

                            $openList[] = ['f'=>$fNew,'pos'=>[$i-1,$j-1]];

                            // Update the details of this cell
                            $cellDetails[$i-1][$j-1]['f'] = $fNew;
                            $cellDetails[$i-1][$j-1]['g'] = $gNew;
                            $cellDetails[$i-1][$j-1]['h'] = $hNew;
                            $cellDetails[$i-1][$j-1]['parent_i'] = $i;
                            $cellDetails[$i-1][$j-1]['parent_j'] = $j;

                        }
                    }
                }


                // Only process this cell if this is a valid one
                if (isValid($i+1, $j+1) == true) {
                    // If the destination cell is the same as the
                    // current successor
                    if (isDestination($i+1, $j+1, $dest) == true) {
                        // Set the Parent of the destination cell
                        $cellDetails[$i][$j+1]['parent_i'] = $i;
                        $cellDetails[$i][$j+1]['parent_j'] = $j;
                        printf("The destination cell is found\n");
                        tracePath($cellDetails, $dest);
                        $foundDest = true;
                        return;
                    }
                    // If the successor is already on the closed
                    // list or if it is blocked, then ignore it.
                    // Else do the following
                    else if (($closedList[$i+1][$j+1] == false)
                                && isUnBlocked($i+1, $j+1) == true) {
                        $gNew = $cellDetails[$i][$j]['g'] + 3.0;
                        $hNew = calculateHValue($i+1, $j+1, $dest);
                        $fNew = $gNew + $hNew;
                        // If it isn’t on the open list, add it to
                        // the open list. Make the current square
                        // the parent of this square. Record the
                        // f, g, and h costs of the square cell
                        //                OR
                        // If it is on the open list already, check
                        // to see if this path to that square is
                        // better, using 'f' cost as the measure.
                        if ($cellDetails[$i+1][$j+1]['f'] == PHP_FLOAT_MAX
                            || $cellDetails[$i+1][$j+1]['f'] > $fNew) {
                            // $openList.insert(make_pair(
                            //     fNew, make_pair(i - 1, j)));

                            $openList[] = ['f'=>$fNew,'pos'=>[$i+1,$j+1]];

                            // Update the details of this cell
                            $cellDetails[$i+1][$j+1]['f'] = $fNew;
                            $cellDetails[$i+1][$j+1]['g'] = $gNew;
                            $cellDetails[$i+1][$j+1]['h'] = $hNew;
                            $cellDetails[$i+1][$j+1]['parent_i'] = $i;
                            $cellDetails[$i+1][$j+1]['parent_j'] = $j;

                        }
                    }
                }


                // Only process this cell if this is a valid one
                if (isValid($i+1, $j-1) == true) {
                    // If the destination cell is the same as the
                    // current successor
                    if (isDestination($i+1, $j-1, $dest) == true) {
                        // Set the Parent of the destination cell
                        $cellDetails[$i+1][$j-1]['parent_i'] = $i;
                        $cellDetails[$i+1][$j-1]['parent_j'] = $j;
                        printf("The destination cell is found\n");
                        tracePath($cellDetails, $dest);
                        $foundDest = true;
                        return;
                    }
                    // If the successor is already on the closed
                    // list or if it is blocked, then ignore it.
                    // Else do the following
                    else if (($closedList[$i+1][$j-1] == false)
                                && isUnBlocked($i+1, $j-1) == true) {
                        $gNew = $cellDetails[$i][$j]['g'] + 3.0;
                        $hNew = calculateHValue($i+1, $j-1, $dest);
                        $fNew = $gNew + $hNew;
                        // If it isn’t on the open list, add it to
                        // the open list. Make the current square
                        // the parent of this square. Record the
                        // f, g, and h costs of the square cell
                        //                OR
                        // If it is on the open list already, check
                        // to see if this path to that square is
                        // better, using 'f' cost as the measure.
                        if ($cellDetails[$i+1][$j-1]['f'] == PHP_FLOAT_MAX
                            || $cellDetails[$i+1][$j-1]['f'] > $fNew) {
                            // $openList.insert(make_pair(
                            //     fNew, make_pair(i - 1, j)));

                            $openList[] = ['f'=>$fNew,'pos'=>[$i+1,$j-1]];

                            // Update the details of this cell
                            $cellDetails[$i+1][$j-1]['f'] = $fNew;
                            $cellDetails[$i+1][$j-1]['g'] = $gNew;
                            $cellDetails[$i+1][$j-1]['h'] = $hNew;
                            $cellDetails[$i+1][$j-1]['parent_i'] = $i;
                            $cellDetails[$i+1][$j-1]['parent_j'] = $j;

                        }
                    }
                }


    }
}