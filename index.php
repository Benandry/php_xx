<?php


class Encounter
{

    public const RESULT_WINNER = 1;
    public const RESULT_LOSER = -1;
    public const RESULT_DRAW = 0;
    public const RESULT_POSSIBILITIES = [self::RESULT_WINNER, self::RESULT_LOSER, self::RESULT_DRAW];

    public static function probabilityAgainst(Player $playerOne, Player $playerTwo)
    {
        return 1 / (1 + (10 ** (($playerTwo->getLevel() - $playerOne->getLevel()) / 400)));
    }

    public static  function setNewLevel(Player &$playerOne, Player $playerTwo, int $playerOneResult)
    {
        if (!in_array($playerOneResult, self::RESULT_POSSIBILITIES)) {
            trigger_error(sprintf('Invalid result. Expected %s', implode(' or ', self::RESULT_POSSIBILITIES)));
        }

        $playerOne->setLevel($playerOne->getLevel() + (int) (32 * ($playerOneResult - self::probabilityAgainst($playerOne, $playerTwo))));
    }
}

class Player
{

    public function __construct(private int $level) {}

    public function setLevel(int $level): self
    {
        $this->level = $level;
        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}


$greg = new Player(400);
$jade = new Player(800);

echo sprintf(
    'Greg à %.2f%% chance de gagner face a Jade',
    Encounter::probabilityAgainst($greg, $jade) * 100
) . PHP_EOL;

// Imaginons que greg l'emporte tout de même.
Encounter::setNewLevel($greg, $jade, Encounter::RESULT_WINNER);
Encounter::setNewLevel($jade, $greg, Encounter::RESULT_LOSER);

echo sprintf(
    'les niveaux des joueurs ont évolués vers %s pour Greg et %s pour Jade',
    $greg->getLevel(),
    $jade->getLevel()
);


class Pont
{
    private const SURFACE_TEXT = "Ce pont mesure %d m²";

    private float $longueur;
    private float $largeur;

    public function setLongueur(float $longueur): self
    {
        self::validatedSize($longueur);

        $this->longueur = $longueur;
        return $this;
    }


    public function setLargeur(float $largeur): self
    {
        self::validatedSize($largeur);

        $this->largeur = $largeur;
        return $this;
    }

    public static function validatedSize(float $size): void
    {
        if ($size < 0) {
            trigger_error("Le taille est trop court min 1", E_USER_ERROR);
        }
    }

    public function getSurface()
    {
        return $this->longueur * $this->largeur;
    }

    public function getSurfaceText(): string
    {
        return printf(self::SURFACE_TEXT, $this->getSurface());
    }
}

$pont = new Pont();

$pont->setLongueur(23);
$pont->setLargeur(23);

$pont->getSurfaceText();


class TopChanteur

{

    public function __invoke(...$args)

    {

        return ucwords(sprintf('%s %s%s', ...$args));
    }
}


class CharlyEtLulu
{

    public function __construct(private TopChanteur $topChanteur) {}


    public function __call($method, $arguments)
    {

        return ($this->topChanteur)($method, ...$arguments);
    }
}


$queen = (new CharlyEtLulu(new TopChanteur))->freddy('mer', 'cury');

echo $queen; // Outputs: Mer Curry

exit(0);
