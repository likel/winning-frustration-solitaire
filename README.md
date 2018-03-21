# winning-frustration-solitaire
This simple class calculates the chance of winning Frustration Solitaire given two values, Cards (13) and Suits (4). It's developed in PHP and uses Rook Polynomials to calculate the chance of winning.

Frustration is a simple and fast solitaire game that relies purely on luck rather than on skill, similar to but opposite of Hit or Miss. As in the latter game, the player deals the cards, and says "ace" when drawing the first card, "two" for the second, then "three, four... nine, ten, jack, queen, king" then starts again with "ace." If the rank of a dealt card matches the rank uttered by the player while dealing it, the game is lost; the game is won if the sequence is successfully repeated four times (and the entire deck is thus dealt out) without any word/card matches causing a loss.

The game has been the subject of several mathematical studies; the odds of winning are approximately 1.6%.

https://en.wikipedia.org/wiki/Frustration_(solitaire)
https://arxiv.org/pdf/math/0703900.pdf

# How to run
Run example.php from the CLI, e.g. in linux: $ php example.php
Run example.php in a web browser
