<?php

namespace App\Domain\Models;

class KeyboardsModel extends BaseModel
{
    /**
    * Fetches a paginated list of keyboards.
    *
    * @return array|False The list of keyboards.
    */
    function getKeyboards(): array | False
    {
        // NOTE: for now, the assignment doesn't impose the addition of filters
        // however, we will eventually need to add a filters parameter

        $sql = "SELECT * FROM keyboards WHERE 1 = 1";

        return $this->paginate($sql);
    }

    /**
    * Fetches a specific keyboard via it's keyboard_id.
    *
    * @param int $keyboard_id The keyboard's id.
    *
    * @return array|False A detailed description of the keyboard.
    */
    function getKeyboardById(int $keyboard_id): array | False
    {
        $sql = "SELECT * FROM keyboards WHERE keyboard_id = :keyboard_id";
        $conditions = [
            'keyboard_id' => $keyboard_id
        ];

        return $this->fetchSingle($sql, $conditions);
    }

    /**
    * Fetches a paginated list of reviews tied to a specific keyboard.
    *
    * @param int $keyboard_id The keyboard's id.
    *
    * @return array|False The list of reviews.
    */
    function getKeyboardReviews(int $keyboard_id): array | False
    {
        $sql = "SELECT * FROM reviews WHERE keyboard_id = :keyboard_id";

        return $this->paginate($sql, [
            'keyboard_id' => $keyboard_id
        ]);
    }
}
