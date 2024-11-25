# Contexte

Stocke les informations permettant de réinstancier le contrôleur dans son état initial sur une interaction utilisateur.<br/>
Le contexte est local au contrôleur `$this->context` et contient un array() d'informations.<br/>
Entre deux requêtes, typiquement l'affichage d'une page et la réception de l'interaction utilisateur, le contexte est stocké dans la Session.


## Ce qui y est stocké

- `context['controller']` : nom complet de la classe du contrôleur à réinstancier

- `context['params']` : les paramètres fournis lors de l'instanciation du contrôleur et permettant de le réinstancier

- `context['stack']` : *RESERVED FOR FUTURE USE*


## Que pouvez-vous y stocker?

Ce que vous voulez, par exemple le numéro de page courante quand un contrôleur affiche une liste paginée, un type de rechercher ou des paramètres de recherches.

Exemple pour un contrôleur recherchant des articles par auteur 'toto' et en affichant une liste paginée:
```
$this->context['params']['search_type'] = "author";
$this->context['params']['search_criteria'] = "toto";
$this->context['page_num'] = 0;
```

Ou pour l'affichage d'un des articles, sans effacer les informations précédentes pour pouvoir revenir au premier sur sa page courante:
```
$this->context['params']['article_id'] = 123456;
```


## References
[Controllers](./Controllers.md)
