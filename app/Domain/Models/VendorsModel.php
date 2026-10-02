<?php

namespace App\Domain\Models;

class VendorsModel extends BaseModel
{
    /*
        Returns the list of available keyboards and mice
        @returns array
    */
    function getVendors(array $filters): array
    {
        $sql = "SELECT * FROM vendors WHERE 1 = 1";
        $conditions = [];

        $name = $filters['name'] ?? '';
        if (!empty($name)) {
            $sql .= " AND name LIKE CONCAT('%', :name, '%')";
            $conditions['name'] = $name;
        }

        $country = $filters['country'] ?? '';
        if (!empty($country)) {
            $sql .= " AND country LIKE CONCAT('%', :country, '%')";
            $conditions['country'] = $country;
        }

        // TODO: Append the LIMIT
        return $this->paginate($sql, $conditions);
    }

    function getVendorsById(int $vendor_id): array | False
    {
        $sql = "SELECT * FROM vendors WHERE vendor_id = :vendor_id";

        return $this->fetchSingle($sql, [
            'vendor_id' => $vendor_id
        ]);
    }

    function getVendorSwitches(int $vendor_id): array | False
    {
        $sql = "SELECT * FROM switches WHERE vendor_id = :vendor_id";

        return $this->fetchSingle($sql, [
            'vendor_id' => $vendor_id
        ]);
    }
}
