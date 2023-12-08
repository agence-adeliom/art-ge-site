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
