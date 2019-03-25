<?php

function get_sorted_virtualmachine_list() {
    include_once(CLASS_PATH . '/VirtualMachine.php');
    include_once (CLASS_PATH . '/ApiConnector.php');
    require_once(CLASS_PATH . '/DatabasePDO.php');


    $api_connection = new ApiConnector;

    $virtual_machine_list = array();

    foreach ($api_connection->get_data() as $row) {
        $virtual_machine = new VirtualMachine(
            $row->HostSystem,
            $row->customer,
            $row->disk_size,
            $row->env,
            $row->latency,
            $row->memory,
            $row->name,
            $row->omgeving,
            $row->vCPU
        );
        $virtual_machine_list[$row->name] = $virtual_machine;
    }

    ksort($virtual_machine_list);

    return $virtual_machine_list;
}


function vm_relation_add($environment_id, $vm_name_from, $vm_name_to, $description, $bidirectional) {
    $pdo = new DatabasePDO();
    $conn = $pdo->get();

    $data = [
        'environment_id' => 1,
        'vm_id_from' => "ASDFASDFA",
        'vm_id_to' => "ASDFADSF",
        'relation_description' => "FUCK DEZE SHIT",
    ];

    var_dump($environment_id, $vm_name_from, $vm_name_to, $description, $bidirectional);

    $query = "INSERT INTO env_vm_relation (`environment_id`,`vm_id_from`,`vm_id_to`,`relation_description`)
	VALUES(:environment_id, :vm_id_from, :vm_id_to, :relation_description);";

    try{
        $statement = $conn->prepare($query);
        $statement->execute($data);
    } catch(PDOException $e) {
        echo "Oops er ging iets mis {$e->getMessage()}";
    }

    if ($bidirectional == 1){

        $query = "INSERT INTO env_vm_relation (`environment_id`, `vm_id_from`, `vm_id_to`, `relation_description`) VALUES (:environment_id, :vm_to, :vm_from, :description);";

        try{
            $statement = $conn->prepare($query);
            $statement->execute($data);
        } catch(PDOException $e) {
            echo "Oops er ging iets mis {$e->getMessage()}";
        }

    }


}