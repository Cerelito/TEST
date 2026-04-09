<?php
class Validator
{
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $label): self
    {
        if (empty(trim($this->data[$field] ?? ''))) {
            $this->errors[$field] = "{$label} es requerido.";
        }
        return $this;
    }

    public function email(string $field, string $label): self
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} no es un correo válido.";
        }
        return $this;
    }

    public function maxLen(string $field, int $max, string $label): self
    {
        if (strlen($this->data[$field] ?? '') > $max) {
            $this->errors[$field] = "{$label} no puede superar {$max} caracteres.";
        }
        return $this;
    }

    public function date(string $field, string $label): self
    {
        $val = $this->data[$field] ?? '';
        if (!empty($val) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
            $this->errors[$field] = "{$label} debe tener formato YYYY-MM-DD.";
        }
        return $this;
    }

    public function inList(string $field, array $list, string $label): self
    {
        if (!in_array($this->data[$field] ?? '', $list, true)) {
            $this->errors[$field] = "{$label} tiene un valor no permitido.";
        }
        return $this;
    }

    public function passes(): bool  { return empty($this->errors); }
    public function fails(): bool   { return !empty($this->errors); }
    public function errors(): array { return $this->errors; }

    public function firstError(): string
    {
        return reset($this->errors) ?: '';
    }
}
