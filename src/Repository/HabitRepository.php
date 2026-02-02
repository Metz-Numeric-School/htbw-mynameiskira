<?php
namespace App\Repository;

use App\Entity\Habit;
use App\Utils\EntityMapper;
use Mns\Buggy\Core\AbstractRepository;

class HabitRepository extends AbstractRepository
{
    public function findAll()
    {
        $habits = $this->getConnection()->query("SELECT * FROM habits");
        return EntityMapper::mapCollection(Habit::class, $habits->fetchAll());
    }

    public function find(int $id)
    {
        $sql = "SELECT * FROM habits WHERE id = :id";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return EntityMapper::map(Habit::class, $stmt->fetch());
    }

    public function findByUser(int $userId)
    {
        // Requête préparée pour sécuriser le filtrage par ID utilisateur
        $sql = "SELECT * FROM habits WHERE user_id = :user_id";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return EntityMapper::mapCollection(Habit::class, $stmt->fetchAll());
    }

    /**
     * Compte le nombre d'habitudes actives pour un utilisateur
     */
    public function countByUser(int $userId): int
    {
        $stmt = $this->getConnection()->prepare("SELECT COUNT(*) as total FROM habits WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function insert(array $data = array())
    {
        // Insertion sécurisée via requête préparée pour éviter les injections via le nom ou la description
        $sql = "INSERT INTO habits (user_id, name, description, created_at) VALUES (:user_id, :name, :description, NOW())";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null
        ]);

        return $this->getConnection()->lastInsertId();
    }

    /**
     * Calcule le nombre de jours consécutifs où l'utilisateur a complété au moins une habitude
     */
    public function getStreak(int $userId): int
    {
        $pdo = $this->getConnection();

        $sql = "
            SELECT DISTINCT log_date
            FROM habit_logs hl
            JOIN habits h ON hl.habit_id = h.id
            WHERE h.user_id = :user_id AND hl.status = 1
            ORDER BY log_date DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $dates = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $streak = 0;
        $today = new \DateTime();
        foreach ($dates as $dateStr) {
            $date = new \DateTime($dateStr);
            if ($date->format('Y-m-d') === $today->format('Y-m-d')) {
                $streak++;
                $today->modify('-1 day');
            } elseif ($date->format('Y-m-d') === $today->format('Y-m-d')) {
                // continue streak
                $streak++;
                $today->modify('-1 day');
            } else {
                break;
            }
        }

        return $streak;
    }

}
