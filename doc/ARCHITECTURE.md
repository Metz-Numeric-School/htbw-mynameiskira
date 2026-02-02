# Architecture d'Hébergement - MyHabitTracker

## Vue d'ensemble

Ce document décrit l'architecture technique d'hébergement de l'application MyHabitTracker, en tenant compte des principes d'élasticité, de dimensionnement et de coûts.

---

## 1. Choix de la Plateforme d'Hébergement

### 1.1 Solution : VPS avec aaPanel

**Avantages :**
- Contrôle total sur l'environnement serveur
- Coût prévisible et maîtrisé
- Performance dédiée (pas de partage de ressources)
- Flexibilité de configuration
- Interface de gestion intuitive (aaPanel)
- Support de Let's Encrypt intégré
- Gestion simplifiée des bases de données et FTP

**Inconvénients :**
- Nécessite des compétences en administration système
- Responsabilité de la maintenance et des mises à jour
- Scalabilité limitée par les ressources du serveur

## 2. Architecture Technique

### 2.1 Stack Technologique (LNMP)

```
┌─────────────────────────────────────────┐
│           Utilisateurs Web              │
└──────────────┬──────────────────────────┘
               │ HTTPS (443)
               ▼
┌─────────────────────────────────────────┐
│         Nginx (Reverse Proxy)           │
│  - Gestion SSL/TLS (Let's Encrypt)      │
│  - Compression gzip                     │
│  - Cache statique                       │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│           PHP-FPM 8.3                   │
│  - Traitement des requêtes PHP          │
│  - Pool de workers configurables        │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│           MySQL 5.7                     │
│  - Base de données relationnelle        │
│  - Optimisation des requêtes            │
└─────────────────────────────────────────┘
```

### 2.2 Composants de l'infrastructure

**Serveur Web : Nginx**

**Moteur PHP : PHP-FPM 8.3**

**Base de données : MySQL 5.7**

**Panneau de contrôle : aaPanel**

