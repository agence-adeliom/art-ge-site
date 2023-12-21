# Lancer le projet

```
ddev start
```

# Lancer les migrations

```
ddev console doctrine:migrations:migrate -n
```

# Lancer les fixtures

Avant de lancer cette commande, 3 fichiers sont à ajouter manuellement :

- ponderations.csv dans `/var`
- ExportCPxCOMMUNES_nov2023.txt dans `/var/datas`
- ExportCommunes_nov2023.txt dans `/var/datas`

```
ddev console doctrine:fixtures:load -n
```

# Déployer 

Pour déployer le site
```
ddev deployer deploy
```

Le dossier .secrets doit contenir :

- .inventory.yaml (ne pas commiter)
- age-key.txt (ne pas commiter)
- decrypt.sh
- encrypt.sh
- id_ed25519 (ne pas commiter)
- public-age-keys.txt

Pour chiffrer le fichier `.secrets/.inventory.yaml`

```bash
chmod +x ./.secrets/encrypt.sh # si première fois 
./.secrets/encrypt.sh .secrets/.inventory.yaml
```

Pour déchiffrer le fichier `.secrets/.inventory.yaml`

```bash
chmod +x ./.secrets/decrypt.sh # si première fois
./.secrets/decrypt.sh .secrets/.inventory.yaml
```

⚠️ Attention ne pas commiter le fichier `inventory.yaml` non chiffré
⚠️ Attention ne pas commiter le fichier `age-key.txt` tout court
⚠️ Attention ne pas commiter le fichier `id_ed25519` tout court

# API

La doc api se trouve dans doc/ART-GE.postman_collection.json

# Infos

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
