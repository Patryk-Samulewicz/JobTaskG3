<?php
declare(strict_types=1);

namespace App\Form;

use App\DB;
use PDO;
use PDOException;

class Form
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::getConnection();
    }

    public function handleFormSubmission(array $postData): array
    {
        $formObject = FormObject::fromPost($postData);
        $errors = $formObject->isValid();

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($this->save_form($formObject)) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to save form data.']];
        }
    }

    private function save_form(FormObject $formObject): bool
    {
        $query = "INSERT INTO zadanie (name, surname, email, phone, choose, client_no, account, agreement1, agreement2, user_info, agreement3) 
                  VALUES (:name, :surname, :email, :phone, :choose, :client_no, :account, :agreement1, :agreement2, :user_info, :agreement3)";
        try {
            $exec = $this->db->prepare($query);
            return $exec->execute($formObject->toDbSave());
        } catch (PDOException $e) {
            // Usually log the error to some file or service
            return false;
        }
    }

    public function counterSurname(string $surname): ?int
    {
        $query = "SELECT COUNT(*) as count FROM zadanie WHERE surname = :surname";
        try {
            $exec = $this->db->prepare($query);
            $exec->execute(['surname' => $surname]);
            return (int)$exec->fetchColumn();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function counterEmailLike(string $email, string $side = 'both'): ?int
    {
        if ($side === 'left') {
            $query = "SELECT COUNT(*) as count FROM zadanie WHERE email LIKE :email";
            $email = $email . '%';
        } elseif ($side === 'right') {
            $query = "SELECT COUNT(*) as count FROM zadanie WHERE email LIKE :email";
            $email = '%' . $email;
        } else {
            $query = "SELECT COUNT(*) as count FROM zadanie WHERE email LIKE :email";
            $email = '%' . $email . '%';
        }
        try {
            $exec = $this->db->prepare($query);
            $exec->execute(['email' => $email]);
            return (int)$exec->fetchColumn();
        } catch (PDOException $e) {
            return null;
        }
    }
}