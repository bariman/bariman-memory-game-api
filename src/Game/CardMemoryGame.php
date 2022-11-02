<?php

namespace Drupal\card_game\Game;

/**
 * Service description.
 */
class CardMemoryGame {

  /**
   * Rows.
   *
   * @var int
   */
  protected $rows;

  /**
   * Columns.
   *
   * @var int
   */
  protected $columns;

  /**
   * Total card count.
   *
   * @var int
   */
  public $cardCount;

  /**
   * Unique cards count.
   *
   * @var int
   */
  public $uniqueCardCount;

  /**
   * Unique cards.
   *
   * @var array
   */
  public $uniqueCards;

  /**
   * Dealt cards to the player.
   *
   * @var array
   */
  public $dealtCards;

  /**
   * Game status.
   *
   * @var bool
   */
  public $status;

  /**
   * Validation error message.
   *
   * @var string
   */
  public $message = '';

  /**
   * Method description.
   */
  public function newGame(int $rows, int $columns) {
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
   * Validate input data.
   *
   * @return bool
   *   Status.
   */
  public function validate() {
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
      $this->status = FALSE;
    }
    else {
      $this->status = TRUE;
    }
    return $this->status;
  }

  /**
   * Generate unique cards array.
   */
  protected function generateUniqueCards() {
    $uniqueCards = [];
    for ($x = 65; $x < 65 + $this->uniqueCardCount; $x++) {
      $uniqueCards[] = chr($x);
    }

    $this->uniqueCards = $uniqueCards;
  }

  /**
   * Generate the board.
   */
  protected function generateBoard() {
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
