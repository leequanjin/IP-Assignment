<!-- Author     : Lee Quan Jin -->

<?php

interface Observer {

    public function update(
            Product $oldData,
            Product $newData,
            string $action
    );
}
