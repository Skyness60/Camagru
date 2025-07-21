# Camagru

**Camagru** est une application web développée avec PHP, utilisant une architecture Docker complète avec monitoring intégré via Prometheus et Grafana.

## 🚀 Démarrage rapide

### Prérequis
- Docker
- Docker Compose
- Make (optionnel, pour utiliser les commandes simplifiées)

### Lancement du projet

1. **Cloner le repository**
   ```bash
   git clone git@github.com:Skyness60/Camagru.git
   cd Camagru
   ```

2. **Configurer les variables d'environnement**
   ```bash
   cp .env.example .env
   # Modifier le fichier .env selon vos besoins
   ```

3. **Lancer tous les services**
   ```bash
   # Avec Make (recommandé)
   make up
   
   # Ou directement avec Docker Compose
   docker-compose up -d --build
   ```

4. **Vérifier que tous les services sont actifs**
   ```bash
   make ps
   # ou
   docker-compose ps
   ```

## 🌐 Accès aux services

### Application principale
- **Site web Camagru** : [http://localhost:8080](http://localhost:8080)
- **Application de test** : [http://localhost:8080/test.php](http://localhost:8080/test.php)

### Base de données
- **PHPMyAdmin** : [http://localhost:8083](http://localhost:8083)
  - Serveur : `db`
  - Utilisateur : `user`
  - Mot de passe : `user`
  - Base de données : `camagru`

### Monitoring et métriques

#### Prometheus (Collecte de métriques)
- **Interface Prometheus** : [http://localhost:9090](http://localhost:9090)
- Collecte automatique des métriques de :
  - Nginx (via nginx-exporter)
  - PHP-FPM (via php-fpm-exporter)
  - Redis (via redis-exporter)

#### Grafana (Visualisation des métriques)
- **Interface Grafana** : [http://localhost:3000](http://localhost:3000)
- Identifiants par défaut :
  - Utilisateur : `admin`
  - Mot de passe : `admin`

**Dashboards recommandés à importer :**
- **Nginx Dashboard** : [Dashboard ID 17452](https://grafana.com/grafana/dashboards/17452-nginx/)
- **PHP-FPM Dashboard** : [Dashboard ID 4912](https://grafana.com/grafana/dashboards/4912-kubernetes-php-fpm/)
- **Redis Dashboard** : [Dashboard ID 763](https://grafana.com/grafana/dashboards/763-redis-dashboard-for-prometheus-redis-exporter-1-x/)

#### Configuration de Grafana

1. **Ajouter Prometheus comme source de données :**
   - URL : `http://prometheus:9090`
   - Type : Prometheus

2. **Importer les dashboards :**
   - Aller dans "+" → "Import"
   - Entrer l'ID du dashboard (ex: 17452 pour Nginx)
   - Sélectionner la source de données Prometheus

### Reverse Proxy et Load Balancer
- **Traefik Dashboard** : [http://localhost:8082](http://localhost:8082)
- **Traefik API** : [http://localhost:8081](http://localhost:8081)

### Cache
- **Redis** : Accessible sur le port `6379`
- **Redis Exporter** : [http://localhost:9121](http://localhost:9121)

## 🔧 Commandes utiles

### Avec Make
```bash
# Démarrer tous les services
make up

# Arrêter tous les services
make down

# Voir les logs en temps réel
make logs

# Voir le statut des conteneurs
make ps

# Accéder au conteneur PHP
make sh

# Accéder à la base de données MariaDB
make db

# Redémarrer complètement le projet
make re

# Nettoyage complet (⚠️ Supprime tout!)
make fullclean
```

### Avec Docker Compose
```bash
# Démarrer
docker-compose up -d --build

# Arrêter
docker-compose down -v

# Logs
docker-compose logs -f

# Accéder au conteneur PHP
docker-compose exec php sh

# Accéder à MariaDB
docker-compose exec db mariadb -uuser -puser camagru
```

## 📊 Architecture du monitoring

### Collecte des métriques
- **nginx-exporter** : Expose les métriques Nginx sur le port 9113
- **php-fpm-exporter** : Expose les métriques PHP-FPM sur le port 9253
- **redis-exporter** : Expose les métriques Redis sur le port 9121

### Endpoints de métriques
- **Nginx status** : [http://localhost:8080/stub_status](http://localhost:8080/stub_status)
- **PHP-FPM status** : Accessible via l'exporter
- **Prometheus targets** : [http://localhost:9090/targets](http://localhost:9090/targets)

## 🐛 Dépannage

### Vérifier les services
```bash
# Voir tous les conteneurs
docker ps

# Voir les logs d'un service spécifique
docker-compose logs nginx
docker-compose logs php
docker-compose logs db
```

### Redémarrer un service spécifique
```bash
docker-compose restart nginx
docker-compose restart php
```

### Problèmes courants

1. **Port déjà utilisé** : Modifier les ports dans le fichier `.env`
2. **Base de données inaccessible** : Vérifier que le conteneur `db` est démarré
3. **Métriques non collectées** : Vérifier les targets dans Prometheus

## 📁 Structure du projet

```
Camagru/
├── docker-compose.yml      # Configuration des services
├── Makefile               # Commandes simplifiées
├── .env                   # Variables d'environnement
├── src/                   # Code source de l'application
│   └── public/
│       ├── index.php      # Page d'accueil
│       └── test.php       # Page de test
├── docker/                # Configuration Docker
│   ├── nginx/
│   │   └── default.conf   # Configuration Nginx
│   ├── php/
│   │   ├── Dockerfile     # Image PHP personnalisée
│   │   └── www.conf       # Configuration PHP-FPM
│   └── prometheus/
│       └── prometheus.yml # Configuration Prometheus
└── dashboard_grafana.txt  # Liens vers les dashboards
```

## 🔗 Liens rapides

- **Application** : [http://localhost:8080](http://localhost:8080)
- **PHPMyAdmin** : [http://localhost:8083](http://localhost:8083)
- **Grafana** : [http://localhost:3000](http://localhost:3000)
- **Prometheus** : [http://localhost:9090](http://localhost:9090)
- **Traefik** : [http://localhost:8082](http://localhost:8082)

# ORM PHP - Documentation

## Utilisation de l'ORM

### 1. Initialisation de l'EntityManager
```php
$pdo = new PDO($dsn, $user, $password);
$em = new EntityManager($pdo);
```

### 2. Utilisation d'un Repository
```php
$userRepo = new UserRepository($em);
```

### 3. Trouver une entité par son ID
```php
$user = $userRepo->find(1);
if ($user) {
    echo $user->getUsername();
}
```

### 4. Trouver toutes les entités
```php
$users = $userRepo->findAll();
foreach ($users as $user) {
    echo $user->getUsername();
}
```

### 5. Trouver par critères
```php
$admins = $userRepo->findBy(['role' => 'admin']);
```

### 6. Trouver une seule entité par critères
```php
$user = $userRepo->findOneBy(['email' => 'sami@example.com']);
```

### 7. Créer et persister une entité (si persistance implémentée)
```php
$user = new User();
$user->setUsername('newuser');
$user->setEmail('newuser@example.com');
$em->persist($user);
$em->flush();
```

## Structure recommandée
- Les entités sont dans `app/Model/Entity/`
- Les repositories sont dans `app/Model/Repository/`
- L'EntityManager et l'ORM sont dans `app/Core/ORM/`

## Bonnes pratiques
- Utilisez toujours les repositories pour accéder aux entités.
- Centralisez la logique métier dans des services.
- Ajoutez des tests unitaires pour chaque méthode du repository.

---

**Note** : Assurez-vous que tous les ports mentionnés sont libres sur votre machine avant de lancer le projet.