<?php
/**
 * Frustration is a simple and fast solitaire game that relies purely on luck rather than on skill,
 * similar to but opposite of Hit or Miss. As in the latter game, the player deals the cards, and
 * says "ace" when drawing the first card, "two" for the second, then "three, four... nine, ten,
 * jack, queen, king" then starts again with "ace." If the rank of a dealt card matches the rank
 * uttered by the player while dealing it, the game is lost; the game is won if the sequence is
 * successfully repeated four times (and the entire deck is thus dealt out) without any word/card
 * matches causing a loss.
 *      - https://en.wikipedia.org/wiki/Frustration_(solitaire) 21/03/2018
 *
 * Inspiration and formulae sourced from https://github.com/jaycoskey/FrustrationSolitaire
 *
 * @package     winning-frustration-solitaire
 * @author      Liam Kelly <https://github.com/likel>
 * @copyright   2018 Liam Kelly
 * @license     MIT License <https://github.com/likel/winning-frustration-solitaire/blob/master/LICENSE>
 * @link        https://github.com/likel/winning-frustration-solitaire
 * @version     1.0.0
 */
namespace Likel;

/**
 * This simple class calculates the chance of winning Frustration Solitaire given two values,
 * Cards (13) and Suits (4). It's developed in PHP and uses Rook Polynomials to calculate the
 * chance of winning.
 *
 * BUG: Setting the number of cards and suits to larger numbers may return NaN
 *
 * Can be instantiated like so:
 *      $frustration = new Likel\Frustration();
 *
 * An example calculation for a normal deck of cards
 *      $number_of_cards = 13;
 *      $number_of_suits = 4;
 *      $frustration = new Likel\Frustration($number_of_cards, $number_of_suits);
 *      $chance = $frustration->getChance();
 */
class Frustration
{
    /** @var float The final chance of winning Frustration with the given cards and suits */
    private $chance = 0;

    /** @var int How many different cards are in the deck */
    private $number_of_cards = 0;

    /** @var int How many different suits are in the deck */
    private $number_of_suits = 0;

    /**
     * Construct the Frustration
     * Set the number_of_cards and number_of_suits
     * @param int $number_of_cards How many different cards are in the deck
     * @param int $number_of_suits How many different suits are in the deck
     * @return void
     */
    function __construct($number_of_cards = 13, $number_of_suits = 4)
    {
        $this->number_of_cards = $number_of_cards;
        $this->number_of_suits = $number_of_suits;
        $this->chance = $this->calculateChance();
    }

    /**
     * Main function to calculate the chance with a given number of cards
     * and number of suits
     * @param int $number_of_cards How many different cards are in the deck
     * @param int $number_of_suits How many different suits are in the deck
     * @return void
     */
    public function calculateChance($number_of_cards = 0, $number_of_suits = 0)
    {
        $number_of_cards = empty($number_of_cards) ? $this->number_of_cards : $number_of_cards;
        $number_of_suits = empty($number_of_suits) ? $this->number_of_suits : $number_of_suits;

        // Get the move allows
        $allowed_moves = $this->getMovesAllowed($number_of_cards, $number_of_suits);

        // (number_of_cards * number_of_suits)! all possible moves
        $all_moves = $this->factorial($number_of_cards * $number_of_suits);

        return $allowed_moves / $all_moves;
    }

    /**
     * Return the chance property
     * @param $as_percentage Return the chance as a percentage
     * @return float
     */
    public function getChance($as_percentage = false)
    {
        return $as_percentage ? 100 * $this->chance : $this->chance;
    }

    /**
     * Return the chance property as a %
     * @param $decimal_places How many decimal places to return
     * @return float
     */
    public function getChanceAsPercentage($decimal_places = 3)
    {
        return (float)number_format((float)$this->getChance(true), $decimal_places, '.', '');
    }

    /**
     * Calculate how many moves are allowed on the board using Rook Polynomials
     * @param int $number_of_cards The number of cards
     * @param int $number_of_suits The number of suits
     * @return array
     */
    private function getMovesAllowed($number_of_cards, $number_of_suits)
    {
        // Calculate the rook polynomial for a chessboard that is suits x suits
        $rook = $this->getRookPolynomial($number_of_cards, $number_of_suits);

        $allowed = 0;

        for ($i = 0; $i <= ($number_of_cards * $number_of_suits); $i++) {
            $allowed += ((-1) ** $i) * $rook[$i] * $this->factorial(($number_of_cards * $number_of_suits) - $i);
        }

        return $allowed;
    }

    /**
     * Calculate the rook polynomial for a chessboard that is suits x suits
     * @param int $number_of_cards The number of cards
     * @param int $number_of_suits The number of suits
     * @return array
     */
    private function getRookPolynomial($number_of_cards, $number_of_suits)
    {
        $squares = array();

        for ($i = 0; $i <= $number_of_suits; $i++) {
            $squares[] = $this->getN($i, $number_of_suits, $number_of_suits);
        }

        // Generate rook from squares ^ number of cards
        $rook = $this->polynomialPower($number_of_cards, $squares);

        return $rook;
    }

    /**
     * Calculate N(S) the number of permutations whose set of rank-fixed points includes S
     * @param int $i The current square
     * @param int $x The x axis
     * @param int $y The y axis
     * @return int
     */
    private function getN($i, $x, $y)
    {
        if($i == 0) { return 1; }
        if($i == 1) { return $x * $y; }

        $result = 0;
        for ($r = 1; $r <= $x - $i + 1; $r++) {
            $result += $y * $this->getN($i - 1, $x - $r, $y - 1);
        }
        return $result;
    }

    /**
     * Calculate the polynomial power and product for each square
     * @param int $number_of_cards The number of cards
     * @param array $squares The rook board
     * @return array
     */
    private function polynomialPower($number_of_cards, $squares)
    {
        if ($number_of_cards == 0) { return (1); }
        if ($number_of_cards == 1) { return ($squares); }

        $polynomial_half = $this->polynomialPower((int)($number_of_cards / 2), $squares);
        $product = $this->polynomialProduct($polynomial_half, $polynomial_half);
        $parity = $number_of_cards % 2;
        if ($parity == 1) {
            $product = $this->polynomialProduct($product, $squares);
        }

        return $product;
    }

    /**
     * Calculate the polynomial product of 2 polynomials
     * @param array $polynomial_a Polynomial comparator A
     * @param array $polynomial_b Polynomial comparator B
     * @return array
     */
    private function polynomialProduct($polynomial_a, $polynomial_b)
    {
        $product = array();

        for ($i = 0; $i <= count($polynomial_a); $i++) {
            for ($j = 0; $j <= count($polynomial_b); $j++) {
                $product[$i + $j] += $polynomial_a[$i] * $polynomial_b[$j];
            }
        }

        return $product;
    }

    /**
     * A simple factorial (!) function
     * @param int $n The number to factorialise
     * @return int
     */
    private function factorial($n)
    {
        $factorial = 1;
        for ($x = $n; $x >= 1; $x--) {
            $factorial = $factorial * $x;
        }
        return $factorial;
    }
}
