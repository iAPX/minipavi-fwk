# Commandes (Cmd)

Décris les différentes commandes utilisables à renvoyer à Minipavi via MiniPaviCli.
Ces commandes servent à définir ce qui peut être saisi ou à réaliser une action définitive comme une déconnexion.

Sources : [src/cmd/](../../src/cmd/)

## Cycle de vie
Les Commandes sont renvoyées par les contrôleurs lors d'une interaction utilisateur, pour indiquer à MiniPavi sa prochaine action, comme un champ de saisie, une saisie multiligne ou une déconnexion.
L'action est destinée à MiniPaviFwk pour savoir quelles actions réaliser, la commande est renvoyée à MiniPavi via MiniPaviCli.

Les commandes sont des fonctions statiques générant le array() à destination de MiniPavi, et leur retour usuellement renvoyé par la méthode getCmd() du contrôleur.

Par exemple:
```
    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 18, 16, true, '.');
    }
```


## Commandes usuelles

### ZoneSaisieCmd : zone de saisie (monoligne)
Signature :
```
public static function createMiniPaviCmd(
        Validation $validation,
        int $ligne = 24,
        int $col = 40,
        int $longueur = 1,
        bool $curseur = true,
        string $spaceChar = " ",
        string $replacementChar = '',
        string $prefill = ''
    ): array
```

Source : [src/cmd/ZoneSaisieCmd.php](../../src/cmd/ZoneSaisieCmd.php)

$validation doit être rempli en appelant $this->validation(). Mais vous pouvez tricher, 255 autorise toutes les touches de fonctions.
$ligne la ligne de la zone de saisie
$col la colonne où commence la zone de saisie
$longueur la longueur de saisie autorisée, MiniPavi refusera tout caractère au-delà
$curseur booléen indiquant si on veut que le curseur soit visible. true = curseur visible, false = curseur invisible
$spaceChar caractère à utiliser pour afficher le champ de saisie, et remplaçant aussi tout caractère effacé via la touche [Correction].
Laissez les autres paramêtres tels-que par défaut, ou consultez la doc de MiniPavi pour ce-faire.


Exemple d'une zone de saisie de 16 caractères, remplie de points '.', débutant en ligne 24 et colonne 18:
```
        \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 18, 16, true, '.');
```


### ZoneMessageCmd : saisie de message multiligne
Signature : 
```
public static function createMiniPaviCmd(
        Validation $validation,
        int $ligne = 24,
        int $hauteur = 1,
        bool $curseur = true,
        string $spaceChar = " ",
        string $prefill = '',
        int $col = 1,
        int $longueur = 40
    ): array
```

Source : [src/cmd/ZoneMessageCmd.php](../../src/cmd/ZoneMessageCmd.php)

$validation doit être rempli en appelant $this->validation(). Mais vous pouvez tricher, 255 autorise toutes les touches de fonctions.
$ligne la ligne de début de la zone de saisie multiligne
$hauteur le nombre de lignes
$curseur booléen indiquant si on veut que le curseur soit visible. true = curseur visible, false = curseur invisible
$spaceChar caractère à utiliser pour afficher le champ de saisie, et remplaçant aussi tout caractère effacé via la touche [Correction].
Laissez les autres paramêtres tels-que par défaut, ou consultez la doc de MiniPavi pour ce-faire.


Exemple d'une zone de saisie de message multiligne, débutant en ligne 5, de 4 lignes de hauteur, et remplie de points '.' :
```
        \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd($this->validation(), 5, 4, true, '.');
```


## Autres commandes

### DeconnexionCmd : déconnexion de l'utilisateur
Signature : `public static function createMiniPaviCmd(): array`

Source : [src/cmd/DeconnexionCmd.php](../../src/cmd/DeconnexionCmd.php)

Déconnecte l'usager courant et efface proprement sa Session.
Change le contrôleur courant (et futur) pour DeconnexionController qui ne supporte aucune action, et ne fait que renvoyer une déconnexion.


Exemple d'usage:
```
        return DeconnexionCmd::createMiniPaviCmd();
```


### PushServiceMsgCmd : envoi de messages en Ligne 00 à de multiples [autres] usagers, et chaînage de commande!
Signature : `public static function createMiniPaviCmd(array $uniqueIds, array $messages): array`

Source : [src/cmd/PushServiceMsgCmd.php](../../src/cmd/PushServiceMsgCmd.php)

$uniqueIds est le array() contenant la liste des uniqueId des utilisateurs destinaataires, uniqueId fournis par MiniPaviCli::$uniqueId
$messages est le array() contenant un message Vidéotex (pas Unicode!) à afficher à chacun en ligne 00
Chaque entrée de $messages correspond à l'entrée de $uniqueIds ayant le même index [0] .. [n].
Notez que $uniqueIds ne doit pas contenir un uniqueId plus d'une fois, Minipavi n'honorera qu'un seul, et les autres messages à destination de cet usager.

Cela permet d'envoyer des messages en ligne 00 à de multiples autres utilisateurs, mais ne positionne pas le champ de saisie ou le message à saisir.
Pour cela, contrairement aux autres commandes, généralement renvoyées par getCmd() du contrôleur, elle doit être mise dans `$_SESSION["DIRECTCALL_CMD"]`.
Elle sera traitée prioritairement au champ de saisie défini par getCmd(), qui sera traité dans la foulée.


Exemple d'usage pour envoyer le message $videotexMessage1 en Ligne 00 à l'usager qui a l'ID $anotherUniqueId, $videotexMessage2 à l'usager qui a l'ID $yetAnotherUserUniqueId:
```
            $_SESSION["DIRECTCALL_CMD"] = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd(
                [$anotherUserUniqueId, $yetAnotherUserUniqueId],
                [$videotexMessage1, $videotexMessage2]
            );
```

Vous pouvez consulter [les contrôleurs du service demochat](../../services/demochat/controllers/) pour voir des exemples d'usage avec de multiples messages envoyés à différentes étapes d'utilisation du service.
