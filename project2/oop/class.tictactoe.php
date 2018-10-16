<?php
/***
* File: oop/class.tictactoe.php
* Author: design1online.com, LLC
* Created: 1.31.2012
* License: Public GNU
* Description: tic tac toe game
***/

class tictactoe extends game
{
	var $player = "X";			//whose turn is
	var $board = array();		//the tic tac toe board
	var $totalMoves = 0;		//how many moves have been made so far		
    var $playerXScore = 0;
    var $playerOScore = 0;
	/**
	* Purpose: default constructor
	* Preconditions: none
	* Postconditions: parent object started
	**/
	function tictactoe()
	{
		/**
		* instantiate the parent game class so this class
		* inherits all of the game class's attributes 
		* and methods
		**/
		game::start();
        $this->newBoard();
	}
	
	/**
	* Purpose: start a new tic tac toe game
	* Preconditions: none
	* Postconditions: game is ready to be displayed
	**/
	function newGame()
	{
		//setup the game
		$this->start();
		
		//reset the player
        if($this->isOver() == "X"){
            $this->player = "O";
        }
        else $this->player = "X";
		
        $this->totalMoves = 0;
        $this->newBoard();
	}
    
    /**
	* Purpose: create an empty board
	* Preconditions: none
	* Postconditions: empty board created
	**/
    function newBoard() {
    
        //clear out the board
		$this->board = array();
        
        //create the board
        for ($x = 0; $x <= 2; $x++)
        {
            for ($y = 0; $y <= 2; $y++)
            {
                $this->board[$x][$y] = null;
            }
        }
    }
	
	/**
	* Purpose: run the game until it's tied or someone has won
	* Preconditions: all $_POST content
	* Postconditions: game is in play
	**/
	function playGame($gamedata)
	{
		if (!$this->isOver() && isset($gamedata['move'])) {
			$this->move($gamedata);
        }
			
		//player pressed the button to start a new game
		if (isset($gamedata['newgame'])) {
			$this->newGame();
        }
				
		//display the game
		$this->displayGame();
	}
	
	/**
	* Purpose: display the game interface
	* Preconditions: none
	* Postconditions: start a game or keep playing the current game
	**/
	function displayGame()
	{
		
		//while the game isn't over
		if (!$this->isOver())
		{
			echo "<div id=\"board\">";
			
			for ($x = 0; $x < 3; $x++)
			{
				for ($y = 0; $y < 3; $y++)
				{
					echo "<div class=\"board_cell\">";
					
					//check to see if that position is already filled
					if ($this->board[$x][$y])
						echo "<img src=\"images/{$this->board[$x][$y]}.jpg\" alt=\"{$this->board[$x][$y]}\" title=\"{$this->board[$x][$y]}\" />";
                    
					else
					{
						//let them choose to put an x or o there
						echo "<select name=\"{$x}_{$y}\">
								<option value=\"\"></option>
								<option value=\"{$this->player}\">{$this->player}</option>
							</select>";
                     
                        
					}
					
					echo "</div>";
				}
				
				echo "<div class=\"break\"></div>";
			}
			
			echo "
				<p class=\"turn\">
					<input type=\"submit\" name=\"move\" value=\"Next Player's Turn\" /><br/>
					<b> {$this->player}'s turn.</b></p>
			</div>";
		}
		else
		{
			
			//someone won the game or there was a tie
            if ($this->isOver() != "Tie")
				echo successMsg("Player " . $this->isOver() . ", is the winner. Check leaderboard below");
            else if ($this->isOver() == "Tie")
				echo errorMsg("Its a  tie.");
			echo "<div class=\"ngame\">";
			echo "<p align=\"center\"><input type=\"submit\" name=\"newgame\" value=\"Play Again\" /></p>";
            echo "</div>";
            $this->createLeaderBoard();
        
		}
	}
	

    function createLeaderBoard(){
        //echo $this->isOver()."<br/>";
        
        if($this->isOver() == "O"){
            //echo "hi im O";
            $this->playerOScore+= 1;
        } else if ($this->isOver() == "X"){
            //echo"hi im X";
            $this->playerXScore+= 1;
        }
        echo "<div class=\"lboard\">";
        echo "<h2>LeaderBoard</h2>";
         echo "</div>";
        echo "<p>";
        
        if ($this->playerXScore > $this->playerOScore) {
            echo "<div class=\"leader\">";
            echo "Player X won " . $this->playerXScore . " time(s)<br/>";
            echo "Player O won " . $this->playerOScore . " time(s)<br/>";
            echo "</div>";
        } else {
            echo "<div class=\"leader\">";
            echo "Player O won " . $this->playerOScore . " time(s) <br/>";
            echo "Player X won " . $this->playerXScore . " time(s) <br/>";
            echo "</div>";

        }echo "<br/>";
       
        if(isset($_GET['clear'])) {
            session_destroy();
        }
        echo "<div class=\"cscore\">";
        echo "<a href=\"?clear\">Reset Scores</a>";
        echo "</div>";
        
    }

    
	function move($gamedata)
	{			

		if ($this->isOver())
			return;

		//remove duplicate entries on the board	
		$gamedata = array_unique($gamedata);
		
		foreach ($gamedata as $key => $value)
		{
			if ($value == $this->player)
			{	
				//update the board in that position with the player's X or O 
				$coords = explode("_", $key);
				$this->board[$coords[0]][$coords[1]] = $this->player;

				//change the turn to the next player
				if ($this->player == "X")
					$this->player = "O";
				else
					$this->player = "X";
					
				$this->totalMoves++;
			}
		}
	
		if ($this->isOver())
			return;
	}
	
	/**
	* Purpose: check for a winner
	* Preconditions: none
	* Postconditions: return the winner if found
	**/
	function isOver()
	{
		
		//top row
		if ($this->board[0][0] && $this->board[0][0] == $this->board[0][1] && $this->board[0][1] == $this->board[0][2])
			return $this->board[0][0];
			
		//middle row
		if ($this->board[1][0] && $this->board[1][0] == $this->board[1][1] && $this->board[1][1] == $this->board[1][2])
			return $this->board[1][0];
			
		//bottom row
		if ($this->board[2][0] && $this->board[2][0] == $this->board[2][1] && $this->board[2][1] == $this->board[2][2])
			return $this->board[2][0];
			
		//first column
		if ($this->board[0][0] && $this->board[0][0] == $this->board[1][0] && $this->board[1][0] == $this->board[2][0])
			return $this->board[0][0];
			
		//second column
		if ($this->board[0][1] && $this->board[0][1] == $this->board[1][1] && $this->board[1][1] == $this->board[2][1])
			return $this->board[0][1];
			
		//third column
		if ($this->board[0][2] && $this->board[0][2] == $this->board[1][2] && $this->board[1][2] == $this->board[2][2])
			return $this->board[0][2];
			
		//diagonal 1
		if ($this->board[0][0] && $this->board[0][0] == $this->board[1][1] && $this->board[1][1] == $this->board[2][2])
			return $this->board[0][0];
			
		//diagonal 2
		if ($this->board[0][2] && $this->board[0][2] == $this->board[1][1] && $this->board[1][1] == $this->board[2][0])
			return $this->board[0][2];
			
		if ($this->totalMoves >= 9)
			return "Tie";
	}
}
