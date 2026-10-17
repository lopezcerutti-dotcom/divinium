<?php

declare(strict_types=1);

namespace App\Validators;

/**
 * Validador rigoroso de dados brasileiros (CPF, CNPJ, CEP, Telefone).
 * Implementa cálculo oficial dos dois dígitos verificadores.
 */
class BrazilianDocumentValidator
{
    /**
     * Valida CPF com conferência matemática de dígitos verificadores e rejeição de sequências óbvias.
     */
    public static function isValidCpf(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return false;
        }

        // Rejeitar CPFs com todos os dígitos iguais (ex: 111.111.111-11)
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Cálculo do 1º Dígito Verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $cpf[$i]) * (10 - $i);
        }
        $remainder = $sum % 11;
        $d1 = ($remainder < 2) ? 0 : 11 - $remainder;

        if ((int) $cpf[9] !== $d1) {
            return false;
        }

        // Cálculo do 2º Dígito Verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += ((int) $cpf[$i]) * (11 - $i);
        }
        $remainder = $sum % 11;
        $d2 = ($remainder < 2) ? 0 : 11 - $remainder;

        return (int) $cpf[10] === $d2;
    }

    /**
     * Valida CNPJ com cálculo oficial dos dois dígitos verificadores.
     */
    public static function isValidCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return false;
        }

        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        // 1º Dígito
        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += ((int) $cnpj[$i]) * $weights1[$i];
        }
        $remainder = $sum % 11;
        $d1 = ($remainder < 2) ? 0 : 11 - $remainder;

        if ((int) $cnpj[12] !== $d1) {
            return false;
        }

        // 2º Dígito
        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += ((int) $cnpj[$i]) * $weights2[$i];
        }
        $remainder = $sum % 11;
        $d2 = ($remainder < 2) ? 0 : 11 - $remainder;

        return (int) $cnpj[13] === $d2;
    }

    /**
     * Valida formato de CEP brasileiro (8 dígitos).
     */
    public static function isValidCep(string $cep): bool
    {
        $cleanCep = preg_replace('/\D/', '', $cep);
        return strlen($cleanCep) === 8 && !preg_match('/^0{8}$/', $cleanCep);
    }

    /**
     * Valida número de telefone móvel ou fixo brasileiro com DDD (10 ou 11 dígitos).
     */
    public static function isValidPhone(string $phone): bool
    {
        $cleanPhone = preg_replace('/\D/', '', $phone);
        $len = strlen($cleanPhone);

        if ($len !== 10 && $len !== 11) {
            return false;
        }

        // Validar DDD brasileiro válido (11 a 99)
        $ddd = (int) substr($cleanPhone, 0, 2);
        if ($ddd < 11 || $ddd > 99) {
            return false;
        }

        // Celular de 11 dígitos deve começar com 9 no terceiro dígito
        if ($len === 11 && (int) $cleanPhone[2] !== 9) {
            return false;
        }

        return true;
    }
}
