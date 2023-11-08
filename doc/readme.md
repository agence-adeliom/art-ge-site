La pondération n'est pas impacté par l'espace vert.
Avoir ou non un espace vert ajoute juste certaines questions 
à l'étape "Biodiversité" et "Gestion de l'eau"


Le document excel de pondération divise "Lieux de visite" et "Activité de loisir"
alors qu'en fait la seule différence relève en un seule changement de pondération a
niveau de la réponse "Je n'ai pas de climatisation" a la question "Quelles actions 
avez-vous mises en place pour limiter les consommations d'énergie ?"  de la thématique
"Gestion de l'énergie". La pondération est de 3 pour les "Lieux de visite" et de 2 
pour les "Activités de loisir" lorsqu'il y a une restauration en place.


Il y a donc 102 pondérations par typologie (x2 avec l'offre de restauration, sauf
pour le restaurant qui a forcément une offre de restauration).
Donc (102 * 2) * 3 + 102 = 714 choice_typologie


Exemple de requête qu'on voudrait faire : 
- "le score de la thématique "Gestion de l'eau et de l'érosion" pour les hotels du 
Bas-Rhin qui ont un restaurant mais pas d'espace vert ?"
https://art-grand-est.ddev.site/territoire/alsace?thematiques[]=gestion-des-dechets&thematiques[]=eco-construction&typologies[]=hotel&typologies[]=camping&restauration=true&greenSpace=true
