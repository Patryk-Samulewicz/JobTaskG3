<?php
declare(strict_types=1);

namespace App\Form;

readonly class FormObject
{
    private function __construct(
        private string  $name,
        private string  $surname,
        private string  $email,
        private string  $phone,
        private int     $choose,
        private string  $client_no,
        private ?string $account,
        private bool    $agreement1,
        private bool    $agreement2,
        private ?string $user_info,
        private ?bool   $agreement3 = null,
        private ?int    $id = null
    )
    {
    }

    public static function fromPost(array $postData): self
    {
        return new self(
            $postData['name'],
            $postData['surname'],
            $postData['email'],
            $postData['phone'],
            (int)$postData['choose'],
            $postData['client_no'],
            $postData['account'] ?? null,
            isset($postData['agreement1']) && $postData['agreement1'] === '1',
            isset($postData['agreement2']) && $postData['agreement2'] === '1',
            $postData['user_info'] ?? null,
            isset($postData['agreement3']) && $postData['agreement3'] === '1'
        );
    }

    public static function fromDB(array $dbData): self
    {
        return new self(
            $dbData['name'],
            $dbData['surname'],
            $dbData['email'],
            $dbData['phone'],
            (int)$dbData['choose'],
            $dbData['client_no'],
            $dbData['account'] ?? null,
            (bool)$dbData['agreement1'],
            (bool)$dbData['agreement2'],
            empty($dbData['user_info']) ? null : $dbData['user_info'],
            $dbData['agreement3'] ? (bool)$dbData['agreement3'] : null,
            (int)$dbData['id']
        );
    }

    public function toDbSave(): array
    {
        return [
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'choose' => $this->choose,
            'client_no' => $this->client_no,
            'account' => $this->account,
            'agreement1' => $this->agreement1 ? 1 : 0,
            'agreement2' => $this->agreement2 ? 1 : 0,
            'user_info' => $this->user_info,
            'agreement3' => $this->agreement3 === null ? null : ($this->agreement3 ? 1 : 0),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getChoose(): int
    {
        return $this->choose;
    }

    public function getClientNo(): string
    {
        return $this->client_no;
    }

    public function getAgreement1(): bool
    {
        return $this->agreement1;
    }

    public function getAgreement2(): bool
    {
        return $this->agreement2;
    }

    public function getUserinfo(): ?string
    {
        return $this->user_info;
    }

    public function getAgreement3(): ?bool
    {
        return $this->agreement3;
    }

    public function isValid(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = 'Imię jest wymagane.';
        }

        if (empty($this->surname)) {
            $errors['surname'] = 'Nazwisko jest wymagane.';
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Wymagany jest prawidłowy adres email.';
        }

        if (empty($this->phone) || !preg_match('/^\d{9}$/', $this->phone)) {
            $errors['phone'] = 'Wymagany jest poprawny numer telefonu w formacie 9 cyfr.';
        }

        if (empty($this->client_no) || !preg_match('/^000\d{3}-[A-Z]{5}$/', $this->client_no)) {
            $errors['client_no'] = 'Wymagany jest poprawny numer klienta w formacie 000DDD-WWWWW, gdzie D to cyfra, W to dowolna wielka litera.';
        }

        if (!in_array($this->choose, [1, 2], true)) {
            $errors['choose'] = 'Konieczne jest dokonanie wyboru (1 lub 2).';
        }

        if (!$this->agreement1) {
            $errors['agreement1'] = 'Zgoda 1 jest wymagana.';
        }

        if (!$this->agreement2) {
            $errors['agreement2'] = 'Zgoda 2 jest wymagana.';
        }

        if (!empty($this->account) && !preg_match('/^\d{26}$/', $this->account)) {
            $errors['account'] = 'Wymagany jest poprawny polski numer konta bankowego (26 cyfr).';
        }

        return $errors;
    }

}