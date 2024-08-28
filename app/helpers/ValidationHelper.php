<?php

class ValidationHelper
{
    public static function validate(array $data, array $required): array
    {
        $errors = [];

        foreach ($required as $campo) {
            if (empty($data[$campo])) {
                $errors[] = "El campo '$campo' es obligatorio.";
            }
        }

        if ($errors !== []) {
            ResponseHelper::respondWithJson(
                false,
                400,
                'Errores de validación',
                ['errors' => $errors]
            );
            exit();
        }

        return $errors;
    }

}
