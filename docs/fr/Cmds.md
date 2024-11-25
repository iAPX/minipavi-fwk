# Commandes (Cmd)

Décris les différentes commandes utilisables à renvoyer à Minipavi via MiniPaviCli.<br/>
Ces commandes servent à définir ce qui peut être saisi ou à réaliser une action définitive comme une déconnexion.

Sources : [src/cmd/](../../src/cmd/)


## Cycle de vie
Les Commandes sont renvoyées par les contrôleurs lors d'une interaction utilisateur, pour indiquer à MiniPavi sa prochaine action, comme un champ de saisie, une saisie multiligne ou une déconnexion.<br/>
L'action est destinée à MiniPaviFwk pour savoir quelles actions réaliser, la commande est renvoyée à MiniPavi via MiniPaviCli.

Les commandes sont des fonctions statiques générant le array() à destination de MiniPavi, et leur retour usuellement renvoyé par la méthode getCmd() du contrôleur.

Par exemple:
```
    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 18, 16, true, '.');
    }
```


## Commandes usuelles

### ZoneSaisieCmd : zone de saisie (monoligne)
Signature :
```
public static function createMiniPaviCmd(
        ?int $validation,
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

$validation contient null si vous souhaitez accepter toutes les touches de fonction du Minitel, et sinon un masque binaire que vous pouvez générer à l'aide du [ValidationHelper](../../src/helpers/ValidationHelper.php).<br/>
$ligne la ligne de la zone de saisie.<br/>
$col la colonne où commence la zone de saisie.<br/>
$longueur la longueur de saisie autorisée, MiniPavi refusera tout caractère au-delà.<br/>
$curseur booléen indiquant si on veut que le curseur soit visible. true = curseur visible, false = curseur invisible.<br/>
$spaceChar caractère à utiliser pour afficher le champ de saisie, et remplaçant aussi tout caractère effacé via la touche [Correction].<br/>
Laissez les autres paramêtres tels-que par défaut, ou consultez la doc de MiniPavi pour ce-faire.


Exemple d'une zone de saisie de 16 caractères, remplie de points '.', débutant en ligne 24 et colonne 18 et acceptant ENVOI et SOMMAIRE:
```
        \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(
            \MiniPaviFwk\helpers\ValidationHelper::ENVOI | \MiniPaviFwk\helpers\ValidationHelper::SOMMAIRE,
            24,
            18,
            16,
            true,
            '.'
        );
```


### ZoneMessageCmd : saisie de message multiligne
Signature : 
```
public static function createMiniPaviCmd(
        ?int $validation,
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

$validation contient null si vous souhaitez accepter toutes les touches de fonction du Minitel, et sinon un masque binaire que vous pouvez générer à l'aide du [ValidationHelper](../../src/helpers/ValidationHelper.php), voir [la documentation du helper Validation](./Validation-helper.md).<br/>
$ligne la ligne de début de la zone de saisie multiligne.<br/>
$hauteur le nombre de lignes, 2 au minimum.<br/>
$curseur booléen indiquant si on veut que le curseur soit visible. true = curseur visible, false = curseur invisible.<br/>
$spaceChar caractère à utiliser pour afficher le champ de saisie, et remplaçant aussi tout caractère effacé via la touche [Correction].<br/>
Laissez les autres paramêtres tels-que par défaut, ou consultez la doc de MiniPavi pour ce-faire.<br/>

> [!IMPORTANT]
> MiniPaviFwk ne gère pas les messages avec une seule ligne.
> Dans ce cas vous devez utiliser ZoneSaisieCmd.

Exemple d'une zone de saisie de message multiligne, débutant en ligne 5, de 4 lignes de hauteur, et remplie de points '.' et n'acceptant que la touche ENVOI:
```
        \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd(
            \MiniPaviFwk\helpers\ValidationHelper::ENVOI,
            5,
            4,
            true,
            '.'
        );
```


### InputFormCmd : saisie de formulaire
Signature :
```
public static function createMiniPaviCmd(
        ?int $validation = null,
        array $fields,
        bool $curseur = true,
        string $spaceChar = " ",
    ): array
```

Source : [src/cmd/InputFormCmd.php](../../src/cmd/InputFormCmd.php)

$validation contient null si vous souhaitez accepter toutes les touches de fonction acceptables pour les formulaires (SOMMAIRE | REPETITION | GUIDE | ENVOI) , et sinon un masque binaire que vous pouvez générer à l'aide du [ValidationHelper](../../src/helpers/ValidationHelper.php), voir [la documentation du helper Validation](./Validation-helper.md).<br/>
$fields contient un array de \MiniPaviFwk\helpers\FormField chacun décrivant un des champs de saisie du formulaire, 2 au minimum.<br/>
$curseur est un booléen indiquant si le curseur doit être affiché.<br/>
$spaceChar est le caractère utilisé pour visualiser l'espace de saisie, souvent le point "."<br/>

> [!IMPORTANT]
> MiniPaviFwk ne gère pas les formulaires avec un seul champ.
> Dans ce cas vous devez utiliser ZoneSaisieCmd.

FormField Signature : `public function __construct(int $ligne, int $col, int $longueur, string $prefill = '')`<br/>
Source [src/helpers/FormField.php](../../src/helpers/FormField.php)

$ligne numéro de ligne de ce champ, de 1 à 24.<br/>
$col colonne de début du champ, de 1 à 40.<br/>
$longueur longueur du champ<br/>
$prefill contenu servant à prépopuler le champ, affiché à l'utilisateur et éditable (pratique pour les modifications de données!)


Au retour de la réponse utilisateur, la méthode `formulaire(string $touche, ...)` sera appelée.<br/>
$touche contiendra la touche de fonction utilisée en majuscules, par exemple "ENVOI".<br/>
Les autres paramètres seront alors chacun de type string, un par champ du formulaire.


Exemple:
```
    public function getCmd(): array
    {
        // We define the fields
        $fields = [
            new \MiniPaviFwk\helpers\FormField(4, 7, 34),
            new \MiniPaviFwk\helpers\FormField(5, 10, 31),
            new \MiniPaviFwk\helpers\FormField(6, 15, 5),
        ];
        return \MiniPaviFwk\cmd\InputFormCmd::createMiniPaviCmd(null, $fields, true, ".");
    }

    public function formulaire(string $touche, string $nom, string $prenom, string $cp): ?\MiniPaviFwk\actions\Action
    {
        if ($touche === 'ENVOI') {
            $vdt = $this->displayPrecedentForm($nom, $prenom, $cp);
            return new \MiniPaviFwk\actions\VideotexOutputAction($this, $vdt);
        } elseif ($touche === 'SOMMAIRE') {
            return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
        }
        return null;
    }
```



## Autres commandes

### DeconnexionCmd : déconnexion de l'utilisateur
Signature : `public static function createMiniPaviCmd(): array`

Source : [src/cmd/DeconnexionCmd.php](../../src/cmd/DeconnexionCmd.php)

Déconnecte l'usager courant et efface proprement sa Session.<br/>
Change le contrôleur courant (et futur) pour DeconnexionController qui ne supporte aucune action, et ne fait que renvoyer une déconnexion.


Exemple d'usage:
```
        return DeconnexionCmd::createMiniPaviCmd();
```


### PushServiceMsgCmd : envoi de messages en Ligne 00 à de multiples [autres] usagers, et chaînage de commande!
Signature : `public static function createMiniPaviCmd(array $uniqueIds, array $messages): array`

Source : [src/cmd/PushServiceMsgCmd.php](../../src/cmd/PushServiceMsgCmd.php)

$uniqueIds est le array() contenant la liste des uniqueId des utilisateurs destinaataires, uniqueId fournis par MiniPaviCli::$uniqueId.<br/>
$messages est le array() contenant un message Vidéotex (pas Unicode!) à afficher à chacun en ligne 00.<br/>
Chaque entrée de $messages correspond à l'entrée de $uniqueIds ayant le même index [0] .. [n].<br/>
Notez que $uniqueIds ne doit pas contenir un uniqueId plus d'une fois, Minipavi n'honorera qu'un seul, et les autres messages à destination de cet usager.

Cela permet d'envoyer des messages en ligne 00 à de multiples autres utilisateurs, mais ne positionne pas le champ de saisie ou le message à saisir.<br/>
Pour cela, contrairement aux autres commandes, généralement renvoyées par getCmd() du contrôleur, elle doit être mise dans `$_SESSION["DIRECTCALL_CMD"]`.<br/>
Elle sera traitée prioritairement au champ de saisie défini par getCmd(), qui sera traité dans la foulée.


Exemple d'usage pour envoyer le message $videotexMessage1 en Ligne 00 à l'usager qui a l'ID $anotherUniqueId, $videotexMessage2 à l'usager qui a l'ID $yetAnotherUserUniqueId:
```
            $_SESSION["DIRECTCALL_CMD"] = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd(
                [$anotherUserUniqueId, $yetAnotherUserUniqueId],
                [$videotexMessage1, $videotexMessage2]
            );
```

Vous pouvez consulter [les contrôleurs du service demochat](../../services/demochat/controllers/) pour voir des exemples d'usage avec de multiples messages envoyés à différentes étapes d'utilisation du service.
