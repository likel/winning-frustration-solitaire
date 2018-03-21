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
 * This example can be run from a webbrowser or the CLI like so:
 *      Browser: navigate to example.php
 *      CLI: $ php example.php {number_of_cards} {number_of_suits}
 *           $ php example.php 13 4
 *
 * @package     winning-frustration-solitaire
 * @author      Liam Kelly <https://github.com/likel>
 * @copyright   2018 Liam Kelly
 * @license     MIT License <https://github.com/likel/winning-frustration-solitaire/blob/master/LICENSE>
 * @link        https://github.com/likel/winning-frustration-solitaire
 * @version     1.0.0
 */
require_once("models/Frustration.php");

$number_of_cards = !empty($argv[1]) ? $argv[1] : 13;
$number_of_suits = !empty($argv[2]) ? $argv[2] : 4;

$frustration = new Likel\Frustration($number_of_cards, $number_of_suits);
$chance = $frustration->getChanceAsPercentage();

echo "Playing Frustration Solitaire with {$number_of_cards} cards and {$number_of_suits} suits yields a {$chance}% chance of winning." . PHP_EOL;
