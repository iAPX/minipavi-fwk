# Helper Validation

Ce helper sert à définir les touches de fonctions qui seront acceptées par MiniPavi.

[Source](../../src/helpers/ValidationHelper.php)


## Usage
Pour ZoneSaisieCmd et ZoneMessageCmd, il y a un paramètre `?int $validation` qui prend soit null (toutes les touches sont autorisées), soit un masque binaire contenant 1 (en binaire) pour chaque touche autorisée.


## Autoriser toutes les touches
Contentez-vous de mettre un `null` dans ce paramètre, fin de l'histoire!


## N'autoriser que certaines touches
Créez un masque binaire en faisant un "ou" logique (logical or) pour regrouper la liste des touches de fonctions à accepter.

Par exemple, accepter ENVOI et SOMMAIRE:<br/>
`\MiniPaviFwk\helpers\ValidationHelper::ENVOI | \MiniPaviFwk\helpers\ValidationHelper::SOMMAIRE`

