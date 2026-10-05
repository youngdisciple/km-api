<?php

namespace App\Domain\Models;

class KeyboardsModel extends BaseModel
{
    function getKeyboards(): array | False
    {
        // NOTE: for now, the assignment doesn't impose the addition of filters
        // however, we will eventually need to add a filters parameter

        $sql = "SELECT * FROM keyboards WHERE 1 = 1";

        return $this->paginate($sql);
    }

    function getKeyboardById(int $id): array | False
    {
        $sql = "SELECT * FROM keyboards WHERE keyboard_id = :keyboard_id";
        $conditions = [
            'keyboard_id' => $id
        ];

        return $this->fetchSingle($sql, $conditions);
    }

    function getKeyboardReviews(int $id): array | False
    {
        $sql = "SELECT * FROM reviews WHERE keyboard_id = :keyboard_id";

        return $this->paginate($sql, [
            'keyboard_id' => $id
        ]);
    }
}
