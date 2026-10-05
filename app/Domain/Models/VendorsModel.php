<?php

namespace App\Domain\Models;

class VendorsModel extends BaseModel
{
    /**
    * Fetches a paginated list of vendors.
    *
    * @return array|False The list of vendors.
    */
    function getVendors(array $filters): array
    {
        $sql = "SELECT * FROM vendors WHERE 1 = 1";
        $args = [];

        $name = $filters['name'] ?? '';
        if (!empty($name)) {
            $sql .= " AND name LIKE CONCAT('%', :name, '%')";
            $args['name'] = $name;
        }

        $country = $filters['country'] ?? '';
        if (!empty($country)) {
            $sql .= " AND country LIKE CONCAT('%', :country, '%')";
            $args['country'] = $country;
        }

        // TODO: Append the LIMIT
        return $this->paginate($sql, $args);
    }

    /**
    * Fetches a specific vendor via it's vendor_id.
    *
    * @param int $vendor_id The vendor's id.
    *
    * @return array|False A detailed description of the vendor.
    */
    function getVendorsById(int $vendor_id): array | False
    {
        $sql = "SELECT * FROM vendors WHERE vendor_id = :vendor_id";

        return $this->fetchSingle($sql, [
            'vendor_id' => $vendor_id
        ]);
    }

    /**
    * Fetches a paginated list of switches tied to a specific vendor.
    *
    * @param int $vendor_id The vendor's id.
    *
    * @return array|False The list of switches.
    */
    function getVendorSwitches(int $vendor_id): array | False
    {
        $sql = "SELECT * FROM switches WHERE vendor_id = :vendor_id";

        return $this->paginate($sql, [
            'vendor_id' => $vendor_id
        ]);
    }
}
