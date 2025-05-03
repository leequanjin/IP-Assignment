<!-- Author     : Lee Quan Jin -->

<?php

interface Observer {

    public function update(
        array $oldData,
        array $newData,
        string $action
    );
}

