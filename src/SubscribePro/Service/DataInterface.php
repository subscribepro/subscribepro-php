<?php

namespace SubscribePro\Service;

interface DataInterface
{
    /**
     * @param array $data
     * @return $this
     */
    public function importData(array $data = []);

    /**
     * @return bool
     */
    public function isNew();

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return array
     */
    public function toArray();
}
