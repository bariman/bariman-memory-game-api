<?php

namespace Drupal\card_game\Game;

/**
 * Service description.
 */
class CardMemoryGame
{

    protected $rows;
    protected $columns;
    public $cardCount;
    public $uniqueCardCount;
    public $uniqueCards;
    public $dealtCards;
    public $status;
    public $message = '';

    /**
     * Method description.
     */
    public function newGame(int $rows, int $columns)
    {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->cardCount = $rows * $columns;
        $this->validate();
        if ($this->status) {
            $this->uniqueCardCount = $this->cardCount / 2;
            $this->generateUniqueCards();
            $this->generateBoard();
        }
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $rows = $this->rows;
        $columns = $this->columns;
        if ($rows > 6) {
            $this->message = 'Row count is greater than 6';
        }
        if ($columns > 6) {
            $this->message = 'Column count is greater than 6';
        }
        if ($rows <= 0) {
            $this->message = 'Row count should be greater than 0';
        }
        if ($columns <= 0) {
            $this->message = 'Column count should be greater than 0';
        }
        if ($columns % 2 == 1 && $rows % 2 == 1) {
            $this->message = "Either `rows` or `columns` needs to be an even number.";
        }
        if ($this->message) {
            $this->status = false;
        }
        else {
            $this->status = true;
        }
        return $this->status;
    }

    protected function generateUniqueCards()
    {
        $uniqueCards = [];
        for ($x = 65; $x < 65 + $this->uniqueCardCount; $x++) {
            $uniqueCards[] = chr($x);
        }

        $this->uniqueCards = $uniqueCards;
    }

    protected function generateBoard()
    {
        $deck = array_merge($this->uniqueCards, $this->uniqueCards);
        shuffle($deck);
        $dealtCards = [];
        for ($r = 0; $r < $this->rows; $r++) {
            for ($c = 0; $c < $this->columns; $c++) {
                $dealtCards[$r][$c] = array_shift($deck);
            }
        }

        $this->dealtCards = $dealtCards;
    }

}
