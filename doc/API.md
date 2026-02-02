# Documentation API - MyHabitTracker

## Vue d'ensemble

Cette API permet de récupérer les habitudes des utilisateurs au format JSON.

**URL de base :** `https://anouar.dfs.lan/api`

**Format de réponse :** JSON

**Authentification :** Aucune (API publique)

---

## Endpoints disponibles

### GET /api/habits

Récupère la liste de toutes les habitudes enregistrées dans le système.

#### Requête

```http
GET /api/habits HTTP/1.1
Host: votre-domaine.com
Accept: application/json
```

#### Paramètres

Aucun paramètre requis.

#### Réponse réussie

**Code :** `200 OK`

**Type de contenu :** `application/json`

**Corps de la réponse :**

```json
[
  {
    "id": 1,
    "user_id": 2,
    "name": "Faire du sport",
    "description": "30 minutes de course à pied",
    "created_at": "2026-01-15 10:30:00"
  },
  {
    "id": 2,
    "user_id": 3,
    "name": "Méditation",
    "description": "10 minutes de méditation guidée",
    "created_at": "2026-01-16 08:00:00"
  }
]
```

#### Structure des données

| Champ | Type | Description |
|-------|------|-------------|
| `id` | integer | Identifiant unique de l'habitude |
| `user_id` | integer | Identifiant de l'utilisateur propriétaire |
| `name` | string | Nom de l'habitude |
| `description` | string\|null | Description détaillée de l'habitude |
| `created_at` | datetime | Date et heure de création (format: Y-m-d H:i:s) |

#### Codes d'erreur possibles

| Code | Description |
|------|-------------|
| `500` | Erreur serveur interne |

#### Exemple d'utilisation

**cURL :**

```bash
curl -X GET https://votre-domaine.com/api/habits \
  -H "Accept: application/json"
```

**JavaScript (Fetch API) :**

```javascript
fetch('https://votre-domaine.com/api/habits')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Erreur:', error));
```

**PHP :**

```php
$response = file_get_contents('https://votre-domaine.com/api/habits');
$habits = json_decode($response, true);
```

---

## Notes importantes

### Sécurité

- Cette API est actuellement publique et ne nécessite pas d'authentification
- Les données sensibles des utilisateurs (mots de passe, emails) ne sont pas exposées
- Seules les informations des habitudes sont accessibles

### Limitations

- Pas de pagination actuellement implémentée
- Pas de filtrage par utilisateur
- Pas de limitation de débit (rate limiting)

### Évolutions futures possibles

1. Ajout d'authentification par token JWT
2. Filtrage par utilisateur : `GET /api/habits?user_id=X`
3. Pagination : `GET /api/habits?page=1&limit=20`
4. Endpoints supplémentaires :
   - `GET /api/habits/{id}` - Récupérer une habitude spécifique
   - `POST /api/habits` - Créer une nouvelle habitude
   - `PUT /api/habits/{id}` - Modifier une habitude
   - `DELETE /api/habits/{id}` - Supprimer une habitude
5. Versioning de l'API : `/api/v1/habits`

---

## Support

Pour toute question ou problème concernant l'API, veuillez contacter l'équipe de développement.

**Dernière mise à jour :** 2026-02-02
